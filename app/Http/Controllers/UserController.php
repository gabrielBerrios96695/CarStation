<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el ID del usuario autenticado
        $authUserId = auth()->id();

        // Filtrar usuarios con status 1 y excluir al usuario autenticado
        $users = User::where('status', 1)
                    ->where('id', '!=', $authUserId)
                    ->paginate(5);

        return view('livewire.users.index', compact('users'));
    }


    public function create()
    {
        return view('livewire/users.create');
    }

    public function edit(User $user)
    {
        return view('livewire/users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
{
    // Validación de los datos enviados
    $request->validate([
        'name' => 'required|string|max:255|regex:/^[\pL\s]+$/u', // Nombre con letras y espacios
        'first_lastname' => 'required|string|regex:/^[\pL]+$/u', // Apellido Paterno
        'second_lastname' => 'nullable|string|regex:/^[\pL]+$/u', // Apellido Materno
        'email' => 'required|email|max:255', // Correo único
        'phone_number' => 'required|digits_between:1,10', // Teléfono
        'address' => 'nullable|string|max:255', // Dirección opcional
        'role' => 'required|in:1,2,3', // Roles válidos
        'ci' => 'required|digits_between:1,12', // CI (Cédula de Identidad)
        'ci_image' => 'nullable|file|mimes:jpeg,png,jpg,gif', // Imagen de CI (opcional)
    ]);

    // Actualización de los datos básicos
    $user->update([
        'name' => $request->name,
        'first_lastname' => $request->first_lastname,
        'second_lastname' => $request->second_lastname,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'ci' => $request->ci,
        'role' => $request->role,
    ]);

    // Si el rol es Dueño de Parqueo (rol 2), actualizar dirección e imagen del CI
    if ($request->role == 2) {
        $user->update([
            'address' => $request->address, // Dirección
        ]);

        // Si hay una imagen de CI, guardarla
        if ($request->hasFile('ci_image')) {
            // Eliminar la imagen anterior si existe
            if ($user->ci_image) {
                Storage::delete('public/ci_images/' . $user->ci_image);
            }

            // Subir la nueva imagen
            $imagePath = $request->file('ci_image')->store('ci_images', 'public');
            $user->update(['ci_image' => basename($imagePath)]);
        }
    }

    return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');
}


    public function destroy($id)
    {
        // Obtener el usuario por su ID
        $user = User::findOrFail($id);

        // Cambiar el status a 0 (deshabilitado)
        $user->status = 0; // 0 para deshabilitado
        $user->save();

        // Redirigir con un mensaje de éxito
        return redirect()->route('users.index')->with('success', 'El usuario ha sido deshabilitado correctamente.');
    }

public function store(Request $request)
{
    // Validaciones
    $request->validate([
        'name' => 'required|string|max:255|regex:/^[\pL]+(\s[\pL]+)*$/u', // Solo letras, espacios y acentos
        'first_lastname' => 'required|string|regex:/^[\pL]+$/u', // Solo letras y acentos, sin espacios
        'second_lastname' => 'nullable|string|regex:/^[\pL]+$/u', // Solo letras, Sin espacios y acentos
        'email' => 'required|string|email|max:255|unique:users,email',
        'phone_number' => 'required|digits_between:1,10',
        'address' => 'nullable|string|max:255',
        'role' => 'required|in:1,2,3', // Validación de los roles
        'ci' => 'required|digits_between:1,12', // CI de 12 dígitos
        'ci_image' => 'nullable|file|mimes:jpeg,png,jpg,gif', // Imagen del CI, opcional
        'password' => 'required|string|min:8|confirmed',
    ], [
        'name.regex' => 'El nombre solo puede contener letras y un único espacio.',
        'first_lastname.regex' => 'El apellido paterno solo puede contener letras ',
        'second_lastname.regex' => 'El apellido materno solo puede contener letras ',
        'phone_number.digits' => 'El número de teléfono debe tener 10 dígitos como maximo.',
        'ci.digits' => 'El número de CI debe tener 12 dígitos como maximo.',
        'ci_image.mimes' => 'La imagen del CI debe ser un archivo de tipo jpeg, png, jpg o gif.',
        'ci_image.max' => 'La imagen del CI no debe superar 1MB.',
    ]);

    // Procesar la imagen del CI si el usuario es "Dueño de Parqueo" (role 2)
    $ci_image_path = null;
    if ($request->role == 2 && $request->hasFile('ci_image')) {
        // Renombrar la imagen del CI con el número de CI
        $ci_image_path = $request->file('ci_image')->storeAs(
            'public/ci_images', $request->ci . '.' . $request->file('ci_image')->getClientOriginalExtension()
        );
    }

    // Crear el nuevo usuario
    User::create([
        'name' => $request->name,
        'first_lastname' => $request->first_lastname,
        'second_lastname' => $request->second_lastname,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'address' => $request->address,
        'role' => $request->role,
        'ci' => $request->ci,
        'ci_image' => $ci_image_path, // Guardar la ruta de la imagen del CI si se subió
        'password' => Hash::make($request->password),
        'status' => 1, // Por defecto, el usuario está activo
    ]);

    return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
}

    public function createAdmin()
    {
        return view('livewire.users.createAdmin'); // Asegúrate de tener esta vista
    }
    public function storeAdmin(Request $request)
{
    // Validar los campos del formulario
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:8',
        'phone_number' => 'nullable|string|max:15',
        'address' => 'nullable|string|max:255',
        'ci' => 'nullable|string|max:20',
    ]);

    // Crear el nuevo usuario con la contraseña hasheada
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password), // Hashear la contraseña
        'phone_number' => $request->phone_number,
        'address' => $request->address,
        'ci' => $request->ci,
        'role' => 1, // Rol de administrador
        'status' => 1 // Usuario activo
    ]);

    // Redirigir a la lista de usuarios con un mensaje de éxito
    return redirect()->route('users.index')->with('success', 'Administrador registrado exitosamente.');
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


}
