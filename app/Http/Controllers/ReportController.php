<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlazaReserva;
use App\Models\Parking;
use Barryvdh\DomPDF\Facade\Pdf;


class ReportController extends Controller
{

    public function clientReservations(Request $request)
    {
        $query = PlazaReserva::with(['user', 'plaza.parking']);

        // Filtros
        if ($request->filled('parking_id')) {
            $query->whereHas('plaza', function($q) use ($request) {
                $q->where('parking_id', $request->parking_id);
            });
        }

        if ($request->filled('reservation_date')) {
            $query->where('reservation_date', $request->reservation_date);
        }

        // Obtener reservas filtradas
        $reservations = $query->orderBy('reservation_date', 'desc')->get();

        // Obtener los parqueos para el select
        $parkings = Parking::all();

        return view('reports.client_reservations', compact('reservations', 'parkings'));
    }
    public function exportPdf(Request $request)
    {
        $query = PlazaReserva::with(['user', 'plaza.parking']);

        // Filtros
        if ($request->filled('parking_id')) {
            $query->whereHas('plaza', function($q) use ($request) {
                $q->where('parking_id', $request->parking_id);
            });
        }

        if ($request->filled('reservation_date')) {
            $query->where('reservation_date', $request->reservation_date);
        }

        // Obtener reservas filtradas
        $reservations = $query->orderBy('reservation_date', 'desc')->get();

        // Generar el PDF usando una vista
        $pdf = PDF::loadView('reports.reservations_pdf', compact('reservations'));

        // Descargar el PDF con un nombre específico
        return $pdf->download('reporte_reservas.pdf');
    }
public function clientReservations2(Request $request)
{
    // Validar las fechas de inicio y fin
    $request->validate([
        'start_month' => 'required|date_format:Y-m',
        'end_month' => 'required|date_format:Y-m|after_or_equal:start_month',
    ]);

    // Convertir las fechas en formato Carbon
    $startDate = Carbon::createFromFormat('Y-m', $request->start_month)->startOfMonth();
    $endDate = Carbon::createFromFormat('Y-m', $request->end_month)->endOfMonth();

    // Obtener los usuarios con la cantidad de reservas en el rango de fechas
    $frequentUsers = User::withCount(['reservas' => function ($query) use ($startDate, $endDate) {
        $query->whereBetween('reservation_date', [$startDate, $endDate]);
    }])
    ->orderByDesc('reservas_count') // Ordenar por la cantidad de reservas
    ->get();

    // Pasar los datos a la vista
    return view('reports.frecuentes', compact('frequentUsers', 'startDate', 'endDate'));
}
}
