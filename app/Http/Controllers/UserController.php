<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Mostrar una lista de los usuarios.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $sortField = $request->input('sort', 'id'); // Campo de ordenamiento, por defecto 'id'
        $sortDirection = $request->input('direction', 'asc'); // Dirección de ordenamiento, por defecto 'asc'

        // Asegúrate de que solo permita ordenar por estos campos para evitar inyecciones SQL
        $allowedSorts = ['id', 'name', 'email'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'id';
        }

        $users = User::orderBy($sortField, $sortDirection)->paginate(10);

        return view('livewire/users.index', compact('users', 'sortField', 'sortDirection'));
    }

    /**
     * Mostrar el formulario para crear un nuevo usuario.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('livewire/users.create');
    }

    /**
     * Mostrar el formulario para editar un usuario existente.
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('livewire/users.edit', compact('user'));
    }

    /**
     * Actualizar un usuario existente.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|integer',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');
    }


    /**
     * Marcar un usuario como eliminado (cambiar el estado a 0).
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado permanentemente.');
    }

    /**
     * Almacenar un nuevo usuario.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 1, // Por defecto, el usuario está activo
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }
    
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        // Alternar estado entre activo (1) y eliminado (0)
        $user->status = $user->status == 0 ? 1 : 0; 
        $user->save();

        return redirect()->route('users.index')->with('success', 'Estado del usuario actualizado con éxito.');
    }

    public function exportToExcel()
{
    $users = User::all(); // Obtiene todos los usuarios

    // Crear una nueva hoja de cálculo
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Insertar el logo en la parte superior
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
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
    $sheet->setCellValue('A3', 'Reporte de Usuarios');
    $sheet->getStyle('A3')->getFont()->setSize(16)->setBold(true);
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Configurar encabezados de la hoja de cálculo
    $sheet->setCellValue('A5', 'ID');
    $sheet->setCellValue('B5', 'Nombre');
    $sheet->setCellValue('C5', 'Correo');
    $sheet->setCellValue('D5', 'Rol');
    $sheet->setCellValue('E5', 'Estado');

    // Aplicar estilo a los encabezados
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4CAF50']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    ];
    $sheet->getStyle('A5:E5')->applyFromArray($headerStyle);

    // Añadir datos de los usuarios
    $row = 6;
    foreach ($users as $user) {
        $sheet->setCellValue('A' . $row, $user->id);
        $sheet->setCellValue('B' . $row, $user->name);
        $sheet->setCellValue('C' . $row, $user->email);
        $sheet->setCellValue('D' . $row, $this->getRoleName($user->role));
        $sheet->setCellValue('E' . $row, $user->status ? 'Activo' : 'Eliminado');

        // Estilizar filas alternas
        if ($row % 2 == 0) {
            $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFF1F8E9'],
                ],
            ]);
        }

        $row++;
    }

    // Añadir la fecha de generación del reporte al final
    $sheet->mergeCells('A' . $row . ':E' . $row);
    $sheet->setCellValue('A' . $row, 'Fecha de generación del reporte: ' . now()->format('d/m/Y H:i'));
    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    // Ajustar el ancho de las columnas automáticamente
    foreach (range('A', 'E') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Crear el archivo Excel y descargarlo
    $writer = new Xlsx($spreadsheet);
    $fileName = 'usuarios.xlsx';
    $temp_file = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($temp_file);

    return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
}



    /**
     * Obtener el nombre del rol basado en el código.
     *
     * @param int $role
     * @return string
     */
    private function getRoleName($role)
    {
        switch ($role) {
            case 1:
                return 'Administrador';
            case 2:
                return 'Usuario';
            case 3:
                return 'Cliente';
            default:
                return 'No definido';
        }
    }

}
