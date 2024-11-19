<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportPurchasesController extends Controller
{
    // Mostrar formulario para seleccionar fechas y mostrar el reporte si ya fue enviado
    public function showReport(Request $request)
    {
        $totalRevenue = 0; // Inicializar en 0
        $purchases = collect(); // Inicializar vacío para compras

        // Verificar si se han enviado las fechas
        if ($request->has('start_date') && $request->has('end_date')) {
            // Validar las fechas de inicio y fin
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date', // Asegurar que la fecha final es igual o posterior a la de inicio
            ]);

            // Obtener las compras dentro del rango de fechas
            $purchases = Purchase::whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ])->get();

            // Calcular las ganancias totales
            $totalRevenue = $purchases->sum('amount');
        }

        // Retornar la vista con el formulario y el reporte (si existe)
        return view('reports.purchases_report', compact('purchases', 'totalRevenue'));
    }

    // Método para generar el PDF del reporte
    public function generatePDF(Request $request)
    {
        // Validar las fechas
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Obtener las compras dentro del rango de fechas
        $purchases = Purchase::whereBetween('created_at', [
            Carbon::parse($request->start_date)->startOfDay(),
            Carbon::parse($request->end_date)->endOfDay()
        ])->get();

        // Calcular las ganancias totales
        $totalRevenue = $purchases->sum('amount');

        // Generar el PDF
        $pdf = Pdf::loadView('reports.purchases_report_pdf', [
            'purchases' => $purchases,
            'totalRevenue' => $totalRevenue,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date
        ]);

        // Devolver el PDF al navegador para descargar
        return $pdf->download('reporte_compras_' . Carbon::now()->format('Y_m_d_H_i_s') . '.pdf');
    }
}
