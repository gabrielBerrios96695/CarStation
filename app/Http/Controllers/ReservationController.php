<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlazaReserva;
use App\Models\Purchase;
use App\Models\Parking;
use App\Models\Plaza;

class ReservationController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user(); // Obtener el usuario autenticado
    $parkingId = $request->input('parking_id');

    // Obtener todos los parqueos
    $parkings = Parking::all();

    // Verificar el rol del usuario
    if ($user->role == 1) {
        // Si el rol es 1, mostrar todas las reservas
        if ($parkingId) {
            $reservations = PlazaReserva::whereHas('plaza', function ($query) use ($parkingId) {
                $query->where('parking_id', $parkingId);
            })->with('plaza', 'user','car')->get();
        } else {
            $reservations = PlazaReserva::with('plaza', 'user')->get();
        }
    } elseif ($user->role == 2) {
        // Si el rol es 2, mostrar solo las reservas de los parqueos registrados a su nombre
        // Verifica si el usuario tiene parqueos asociados
        $parkingsForUser = $user->parkings ? $user->parkings->pluck('id') : collect(); // Evitar error si no tiene parqueos

        if ($parkingId) {
            $reservations = PlazaReserva::whereHas('plaza', function ($query) use ($parkingId, $parkingsForUser) {
                $query->where('parking_id', $parkingId)->whereIn('plaza_id', $parkingsForUser);
            })->with('plaza', 'user','car')->get();
        } else {
            $reservations = PlazaReserva::whereIn('plaza_id', $parkingsForUser)
                ->with('plaza', 'user','car')
                ->get();
        }
    } elseif ($user->role == 3) {
        // Si el rol es 3, mostrar solo las reservas realizadas por el usuario
        if ($parkingId) {
            $reservations = PlazaReserva::where('user_id', $user->id)
                ->whereHas('plaza', function ($query) use ($parkingId) {
                    $query->where('parking_id', $parkingId);
                })
                ->with('plaza', 'user','car')
                ->get();
        } else {
            $reservations = PlazaReserva::where('user_id', $user->id)
                ->with('plaza', 'user','car')
                ->get();
        }
    } else {
        // Si el usuario tiene otro rol o no está autenticado, devolver vacío o algún manejo de error
        $reservations = collect();
    }

    return view('livewire.reservas.index', compact('reservations', 'parkings'));
}

    public function store(Request $request)
    {

        $request->validate([
            'plaza_id' => 'required|exists:plazas,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'car_id' => 'required|exists:cars,id'
        ]);
    
        // Verificar si hay reservas en el rango solicitado
        $existingReservations = PlazaReserva::where('plaza_id', $request->plaza_id)
            ->where('reservation_date', $request->reservation_date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function ($query) use ($request) {
                          $query->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();
    
        if ($existingReservations) {
            return redirect()->back()->with('error', 'La hora seleccionada no está disponible para la reserva.');
        }
    
        // Obtener el usuario autenticado
        $userId = auth()->id();
        // Obtener las compras realizadas por el usuario
        $purchasedPackages = Purchase::where('user_id', $userId)
            ->with('package')
            ->get();
    
        // Calcular las horas totales disponibles
        $totalAvailableHours = 0;
        $totalPurchasedHours = 0;
    
        foreach ($purchasedPackages as $purchase) {
            $totalAvailableHours += $purchase->hours;
            $totalPurchasedHours += $purchase->package->hours;
        }
    
        // Calcular la duración de la reserva en horas
        $startTime = strtotime($request->start_time);
        $endTime = strtotime($request->end_time);
        $duration = ($endTime - $startTime) / 3600;
    
        // Verificar si hay suficientes horas disponibles
        if ($duration > $totalAvailableHours) {
            return redirect()->back()->with('error', 'No tienes suficientes horas disponibles para realizar esta reserva.');
        }
    
        // Verificar si las horas de la reserva son mayores a las horas del paquete comprado
        if ($duration > $totalPurchasedHours) {
            return redirect()->back()->with('error', 'Las horas de la reserva exceden las horas del paquete comprado.');
        }
    
        // Crear y guardar la nueva reserva
        $reservation = PlazaReserva::create([
            'plaza_id' => $request->input('plaza_id'),
            'user_id' => $userId,
            'car_id' => $request->input('car_id'),
            'reservation_date' => $request->input('reservation_date'),
            'start_time' => $request->input('start_time'),
            'end_time' => date('H:i:s', strtotime($request->input('end_time')) - 1),
        ]);
    
        // Obtener la plaza asociada a la reserva
        $plaza = Plaza::findOrFail($request->input('plaza_id'));
    
        // Restar las horas utilizadas del total
        $this->deductHours($userId, $duration);
    
        // Generar el ticket PDF
        $pdf = \PDF::loadView('reservas.ticket', [
            'reservation' => $reservation,
            'user' => auth()->user(),
            'plaza' => $plaza, // Pasar la plaza aquí
        ]);
        
        // Guardar el PDF en una carpeta pública
        $pdfPath = public_path('tickets/' . $reservation->id . '_ticket_reserva.pdf');
        $pdf->save($pdfPath);
    
        // Redirigir de nuevo con un mensaje de éxito y la URL del ticket
        return redirect()->back()->with([
            'success' => 'Reserva realizada con éxito.',
            'ticket_url' => asset('tickets/' . $reservation->id . '_ticket_reserva.pdf') // Pasamos la URL para descargar el ticket
        ]);
    }
    



// Método para restar las horas utilizadas
protected function deductHours($userId, $hoursUsed)
{
    // Obtener las compras realizadas por el usuario
    $purchases = Purchase::where('user_id', $userId)->get();

    foreach ($purchases as $purchase) {
        if ($purchase->hours >= $hoursUsed) {
            $purchase->hours -= $hoursUsed; // Restar horas utilizadas
            $purchase->save(); // Guardar cambios
            break; // Salir después de restar de la primera compra válida
        } else {
            $hoursUsed -= $purchase->hours; // Restar horas de la compra
            $purchase->hours = 0; // Agotar esta compra
            $purchase->save(); // Guardar cambios
        }
    }
}


}
