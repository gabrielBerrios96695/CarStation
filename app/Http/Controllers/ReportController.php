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
}
