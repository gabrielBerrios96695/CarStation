<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Plaza; 
use App\Models\PlazaHour;
use App\Models\PlazaReserva;
use App\Models\Purchase;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ParkingController extends Controller
{
    public function index()
{
    // Obtener el usuario autenticado
    $user = auth()->user();

    // Consulta base de estacionamientos con conteo de plazas activas y relación con usuario
    $parkingsQuery = Parking::with(['user']) // Cargamos la relación con el usuario
        ->withCount(['plazas' => function ($query) {
            $query->where('status', 1); // Solo contar plazas con status igual a 1
        }]);

    // Filtrar por rol
    if ($user->role == 1) { 
        // Si el usuario es administrador, mostrar todos los parqueos
        $parkings = $parkingsQuery->get();
    } elseif ($user->role == 2) {
        // Si el usuario es vendedor, mostrar solo sus parqueos
        $parkings = $parkingsQuery->where('user_id', $user->id)->get();
    } else {
        // Si el usuario tiene otro rol, devolver un array vacío o manejarlo como prefieras
        $parkings = collect();
    }

    // Retornar la vista con los datos
    return view('livewire.parkings.index', [
        'parkings' => $parkings,
    ]);
}
   
    public function create()
    {
        return view('livewire.parkings.create');
    }

    public function maps()
{
    $user = auth()->user(); // Obtener el usuario autenticado

    // Verificar el rol del usuario
    if ($user->role == 2) {
        // Si es vendedor (role = 2), mostrar solo sus parqueos
        $parkings = Parking::withCount(['plazas' => function ($query) {
            $query->where('status', 1); // Contar solo plazas activas
        }])
        ->where('user_id', $user->id) // Filtrar por el usuario autenticado
        ->get();
    } else {
        // Si es administrador u otro rol, mostrar todos los parqueos
        $parkings = Parking::withCount(['plazas' => function ($query) {
            $query->where('status', 1); // Contar solo plazas activas
        }])->get();
    }

    return view('livewire.parkings.maps', compact('parkings'));
}


 
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

    // Habilitar plazas deshabilitadas antes de agregar nuevas
    $disabledPlazas = Plaza::where('parking_id', $parking->id)
                            ->where('status', 0) // Plazas deshabilitadas
                            ->limit($desiredNumberOfSpaces - $currentNumberOfSpaces)
                            ->update(['status' => 1]); // Habilitar plazas

    // Volver a contar las plazas después de habilitar
    $currentNumberOfSpaces = Plaza::where('parking_id', $parking->id)->count();

    if ($desiredNumberOfSpaces > $currentNumberOfSpaces) {
        // Agregar plazas adicionales
        for ($i = $currentNumberOfSpaces; $i < $desiredNumberOfSpaces; $i++) {
            Plaza::create(['parking_id' => $parking->id]);
        }
    } elseif ($desiredNumberOfSpaces < $currentNumberOfSpaces) {
        // Cambiar el estado de las plazas extras a 0
        Plaza::where('parking_id', $parking->id)
            ->orderBy('id', 'desc')
            ->take($currentNumberOfSpaces - $desiredNumberOfSpaces)
            ->update(['status' => 0]); // Actualiza el estado a 0 en lugar de eliminar
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
        'number_of_spaces' => 'required|integer|min:1', // Número de plazas
    ]);

    // Crear el parqueo
    $parking = Parking::create([
        'name' => $request->name,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'status' => 1, // Activo por defecto
        'user_id' => auth()->user()->id,
        'opening_time' => $request->opening_time,
        'closing_time' => $request->closing_time,
    ]);

    // Crear las plazas asociadas al parqueo
    for ($i = 0; $i < $request->number_of_spaces; $i++) {
        Plaza::create([
            'parking_id' => $parking->id,
            'status' => 1, // Activa por defecto
        ]);
    }

    return redirect()->route('parkings.index')->with('success', 'Estacionamiento y plazas creados exitosamente.');
}


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
    
    public function view($id, Request $request)
{
    // Obtener el parqueo por ID
    $parking = Parking::findOrFail($id);
    
    // Obtener la fecha de reserva, usando la fecha actual como predeterminada
    $reservationDate = $request->input('reservation_date', date('Y-m-d'));

    // Obtener el usuario autenticado
    $user = auth()->user();

    // Obtener los autos registrados por el usuario autenticado
    $cars = $user->cars()->where('status', 1)->get();

    // Obtener las compras realizadas por el usuario autenticado que correspondan al parqueo seleccionado
    // y que tengan horas mayores a 0
    $purchasedPackages = Purchase::where('user_id', $user->id)
        ->whereHas('package', function ($query) use ($parking) {
            $query->where('parking_id', $parking->id); // Filtrar por el ID del parqueo
        })
        ->where('hours', '>', 0) // Filtrar las compras con más de 0 horas
        ->get();

    // Obtener las plazas disponibles para el parqueo seleccionado
    $plazas = Plaza::where('parking_id', $id)
        ->where('status', 1)
        ->with(['reservations' => function ($query) use ($reservationDate) {
            $query->whereDate('reservation_date', $reservationDate);
        }])
        ->get();

    // Inicializar el array para almacenar horas disponibles por plaza
    $available_hours_by_plaza = [];

    // Calcular horas disponibles para cada plaza
    foreach ($plazas as $plaza) {
        $availableHours = array_map(fn($hour) => sprintf('%02d:00', $hour), range(0, 23));

        foreach ($plaza->reservations as $reservation) {
            $startHour = (int) date('H', strtotime($reservation->start_time));
            $endHour = (int) date('H', strtotime($reservation->end_time));

            // Desmarcar las horas ocupadas
            for ($hour = $startHour; $hour <= $endHour; $hour++) {
                unset($availableHours[$hour]);
            }
        }

        // Almacenar las horas disponibles para la plaza actual
        $available_hours_by_plaza[$plaza->id] = array_values($availableHours);
    }

    return view('livewire.parkings.view', compact('parking', 'plazas', 'cars','available_hours_by_plaza', 'reservationDate', 'purchasedPackages'));
}


    // Método para almacenar una nueva reserva
    public function storeReservation(Request $request)
    {
        $request->validate([
            'plaza_id' => 'required|exists:plazas,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        PlazaReserva::create([
            'plaza_id' => $request->plaza_id,
            'reservation_date' => $request->reservation_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Reserva creada exitosamente.');
    }
  
public function availableHours(Request $request)
{
    $plazaId = $request->input('plaza_id');
    $date = $request->input('date');

    // Obtener las reservas existentes para la plaza y la fecha
    $reservas = Reserva::where('plaza_id', $plazaId)
        ->whereDate('start_time', $date) // Asegúrate de que 'start_time' sea la columna correcta
        ->get();

    // Crear un arreglo de todas las horas (ej. de 00:00 a 23:00)
    $allHours = [];
    for ($i = 0; $i < 24; $i++) {
        $allHours[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
    }

    // Eliminar horas que ya están reservadas
    foreach ($reservas as $reserva) {
        // Suponiendo que 'start_time' y 'end_time' son de tipo 'datetime'
        $startHour = \Carbon\Carbon::parse($reserva->start_time)->format('H:i');
        $endHour = \Carbon\Carbon::parse($reserva->end_time)->format('H:i');
        
        // Quitar las horas ocupadas
        for ($h = (int) $startHour; $h < (int) $endHour; $h++) {
            $hour = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            if (($key = array_search($hour, $allHours)) !== false) {
                unset($allHours[$key]);
            }
        }
    }

    return response()->json(['available_hours' => array_values($allHours)]);
}


}
