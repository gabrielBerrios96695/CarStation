<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Plaza; // Asegúrate de importar el modelo Plaza
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ParkingController extends Controller
{
    /**
     * Mostrar una lista de los estacionamientos.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    // app/Http/Controllers/ParkingController.php
    public function index()
    {
        $sortField = request()->get('sort_field', 'id');
        $sortDirection = request()->get('sort_direction', 'asc');

        $parkings = Parking::withCount('plazas') // Carga el conteo de plazas
            ->orderBy($sortField, $sortDirection)
            ->get();

        return view('livewire.parkings.index', [
            'parkings' => $parkings,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
        ]);
    }

    /**
     * Mostrar el formulario para crear un nuevo estacionamiento.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('livewire.parkings.create');
    }

    /**
     * Mostrar el formulario para editar un estacionamiento existente.
     *
     * @param Parking $parking
     * @return \Illuminate\View\View
     */
    public function edit(Parking $parking)
    {
        $numberOfSpaces = Plaza::where('parking_id', $parking->id)->count();

        return view('livewire.parkings.edit', [
            'parking' => $parking,
            'numberOfSpaces' => $numberOfSpaces
        ]);
    }

    public function update(Request $request, Parking $parking)
    {
        // Validar la entrada del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'number_of_spaces' => 'required|integer|min:0',
        ]);

        // Actualizar el estacionamiento con los datos del formulario
        $parking->update([
            'name' => $request->input('name'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'opening_time' => $request->input('opening_time'),
            'closing_time' => $request->input('closing_time'),
        ]);

        // Ajustar la cantidad de plazas
        $currentNumberOfSpaces = Plaza::where('parking_id', $parking->id)->count();
        $desiredNumberOfSpaces = $request->input('number_of_spaces');

        if ($desiredNumberOfSpaces > $currentNumberOfSpaces) {
            // Agregar plazas adicionales
            for ($i = $currentNumberOfSpaces; $i < $desiredNumberOfSpaces; $i++) {
                Plaza::create(['parking_id' => $parking->id]);
            }
        } elseif ($desiredNumberOfSpaces < $currentNumberOfSpaces) {
            // Eliminar plazas extras
            Plaza::where('parking_id', $parking->id)
                ->orderBy('id', 'desc')
                ->take($currentNumberOfSpaces - $desiredNumberOfSpaces)
                ->delete();
        }

        // Redirigir a la lista de estacionamientos con un mensaje de éxito
        return redirect()->route('parkings.index')->with('success', 'Estacionamiento actualizado con éxito.');
    }

    /**
     * Eliminar un estacionamiento existente.
     *
     * @param Parking $parking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Parking $parking)
    {
        $parking->delete();

        return redirect()->route('livewire.parkings.index')->with('success', 'Estacionamiento eliminado correctamente');
    }

    /**
     * Almacenar un nuevo estacionamiento.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'number_of_spaces' => 'required|integer|min:1', // Añadir validación para el número de plazas
        ]);

        // Crear el parqueo
        $parking = Parking::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 1, // Activo por defecto
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
        ]);

        // Crear las plazas asociadas si hay un número especificado
        $numberOfSpaces = $request->input('number_of_spaces', 0);
        if ($numberOfSpaces > 0) {
            for ($i = 0; $i < $numberOfSpaces; $i++) {
                Plaza::create([
                    'parking_id' => $parking->id,
                ]);
            }
        }

        return redirect()->route('parkings.index')->with('success', 'Estacionamiento creado exitosamente.');
    }

    /**
     * Alternar el estado de un estacionamiento entre activo e inactivo.
     *
     * @param Parking $parking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(Parking $parking)
    {
        $parking->status = !$parking->status;
        $parking->save();

        return redirect()->route('parkings.index')->with('success', 'Estado del estacionamiento actualizado correctamente');
    }
    public function exportToExcel()
    {
        $parkings = Parking::all(); // Obtiene todos los estacionamientos

        // Crear una nueva hoja de cálculo
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Insertar el logo en la parte superior
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo de la Empresa');
        $drawing->setPath(public_path('imagenes/logo.jpeg')); // Ruta del logo
        $drawing->setHeight(50); // Ajusta el tamaño del logo
        $drawing->setCoordinates('A1'); // Ubicación del logo
        $drawing->setOffsetX(10); // Desplazamiento horizontal
        $drawing->setWorksheet($sheet);

        // Fusionar celdas para el título y agregar un encabezado
        $sheet->mergeCells('A1:E2'); // Espacio reservado para el logo
        $sheet->mergeCells('A3:E3');
        $sheet->setCellValue('A3', 'Reporte de Estacionamientos');
        $sheet->getStyle('A3')->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Configurar encabezados de la hoja de cálculo
        $sheet->setCellValue('A5', 'ID');
        $sheet->setCellValue('B5', 'Nombre');
        $sheet->setCellValue('C5', 'Latitud');
        $sheet->setCellValue('D5', 'Longitud');
        $sheet->setCellValue('E5', 'Capacidad');
        $sheet->setCellValue('F5', 'Hora de Apertura');
        $sheet->setCellValue('G5', 'Hora de Cierre');
        $sheet->setCellValue('H5', 'Estado');

        // Aplicar estilo a los encabezados
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4CAF50']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A5:H5')->applyFromArray($headerStyle);

        // Añadir datos de los estacionamientos
        $row = 6;
        foreach ($parkings as $parking) {
            $sheet->setCellValue('A' . $row, $parking->id);
            $sheet->setCellValue('B' . $row, $parking->name);
            $sheet->setCellValue('C' . $row, $parking->latitude);
            $sheet->setCellValue('D' . $row, $parking->longitude);
            $sheet->setCellValue('E' . $row, $parking->capacity);
            $sheet->setCellValue('F' . $row, $parking->opening_time);
            $sheet->setCellValue('G' . $row, $parking->closing_time);
            $sheet->setCellValue('H' . $row, $parking->status ? 'Activo' : 'Inactivo');

            // Estilizar filas alternas
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF1F8E9'],
                    ],
                ]);
            }

            $row++;
        }

        // Añadir la fecha de generación del reporte al final
        $sheet->mergeCells('A' . $row . ':H' . $row);
        $sheet->setCellValue('A' . $row, 'Fecha de generación del reporte: ' . now()->format('d/m/Y H:i'));
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Ajustar el ancho de las columnas automáticamente
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Crear el archivo Excel y descargarlo
        $writer = new Xlsx($spreadsheet);
        $fileName = 'estacionamientos.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }
    public function view(Request $request)
{
    // Obtener todos los estacionamientos
    $parkings = Parking::all();

    // Obtener el ID del parqueo seleccionado, si existe
    $selectedParkingId = $request->input('parking_id');
    $selectedParking = null;
    $plazas = [];

    if ($selectedParkingId) {
        $selectedParking = Parking::find($selectedParkingId);
        // Aquí deberías obtener las plazas asociadas al parqueo
        // Esto es solo un ejemplo, adapta según tu modelo y relaciones
        $plazas = $selectedParking ? $selectedParking->plazas : [];
    }

    return view('livewire.parkings.view', compact('parkings', 'selectedParking', 'plazas'));
}

}
