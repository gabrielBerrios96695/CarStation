<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlazaReserva;
use App\Models\Parking;
use App\Models\Plaza;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $parkingId = $request->input('parking_id');

        // Obtener todos los parqueos
        $parkings = Parking::all();

        // Obtener reservas filtradas por parqueo
        if ($parkingId) {
            $reservations = PlazaReserva::whereHas('plaza', function ($query) use ($parkingId) {
                $query->where('parking_id', $parkingId);
            })->with('plaza', 'user')->get();
        } else {
            $reservations = PlazaReserva::with('plaza', 'user')->get();
        }

        return view('livewire.reservas.index', compact('reservations', 'parkings'));
    }
    public function store(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'plaza_id' => 'required|exists:plazas,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
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

        // Crear y guardar la nueva reserva
        PlazaReserva::create([
            'plaza_id' => $request->input('plaza_id'),
            'user_id' => auth()->id(), // Obtiene el ID del usuario autenticado
            'reservation_date' => $request->input('reservation_date'),
            'start_time' => $request->input('start_time'),
            'end_time' => date('H:i:s', strtotime($request->input('end_time')) - 1), // Restar 1 segundo
        ]);

        // Obtener el id del parqueo asociado a la plaza
        $plaza = Plaza::findOrFail($request->plaza_id); // Utiliza findOrFail para asegurar que la plaza existe
        $parkingId = $plaza->parking_id;

        // Crear el mensaje de éxito personalizado
        $successMessage = sprintf(
            'Reserva de %s a %s realizada con éxito.',
            $request->start_time,
            $request->end_time
        );

        // Redireccionar a la vista de las plazas, pasando la fecha de reserva y el ID del parqueo
        return redirect()->route('parkings.view', ['id' => $parkingId])
                         ->with('success', $successMessage);
    }
}
