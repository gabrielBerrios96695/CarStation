<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Parking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Zxing\QrReader;

class PackagesController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user(); // Obtener el usuario autenticado

    // Crear la consulta base para los paquetes
    $query = Package::query();

    if ($user->role != 1) {
        // Si el rol no es 1 (es decir, es 2 o 3), solo mostrar los paquetes asociados a los parqueos creados por el usuario
        $userId = $user->id;
        $query->whereHas('parking', function ($query) use ($userId) {
            $query->where('created_by', $userId);
        });
    }

    if ($request->has('status') && in_array($request->status, ['0', '1'])) {
        // Filtrar por el estado seleccionado (0 = Inactivo, 1 = Activo)
        $query->where('status', $request->status);
    } else {
        // Si no se pasa un estado, por defecto mostrar solo los paquetes activos (1)
        $query->where('status', 1);
    }

    // Obtener los paquetes filtrados
    $packages = $query->get();

    // Pasar los paquetes a la vista
    return view('livewire.packages.index', compact('packages'));
}






public function create()
{
    // Obtener los parqueos asociados al usuario autenticado
    $userId = auth()->id();
    $parkings = Parking::where('user_id', $userId)->get();

    // Calcular el siguiente número de paquete
    $lastPackage = Package::where('created_by', $userId)->latest()->first();
    $nextPackageNumber = $lastPackage ? (intval(substr($lastPackage->name, -1)) + 1) : 1;
    $packageName = 'Paquete ' . $nextPackageNumber;

    return view('livewire.packages.create', compact('parkings', 'packageName'));
}



public function store(Request $request)
{
    // Validación de los datos
    $request->validate([
        'hours' => 'nullable|integer', // Cambiado de tokens a horas
        'price' => 'required|numeric',
        'parking_id' => 'required|exists:parkings,id',
        'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validaciones
    ]);

    // Obtener el usuario autenticado
    $userId = auth()->id();

    // Calcular el siguiente número de paquete
    $lastPackage = Package::where('created_by', $userId)->latest()->first();
    $nextPackageNumber = $lastPackage ? (intval(substr($lastPackage->name, -1)) + 1) : 1;
    $packageName = 'Paquete ' . $nextPackageNumber;

    // Crear una nueva instancia del paquete
    $package = new Package();
    $package->name = $packageName; // Usar el nombre generado automáticamente
    $package->hours = $request->input('hours'); // Cambiado de tokens a horas
    $package->price = $request->input('price');
    $package->parking_id = $request->input('parking_id');
    $package->created_by = $userId;

    // Manejo de la imagen QR
    if ($request->hasFile('qr_code')) {
        $file = $request->file('qr_code');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('qr_codes', $filename, 'public'); // Guarda la imagen en storage/app/public/qr_codes

        // Verificar si la imagen contiene un código QR
        $qrReader = new QrReader(storage_path('app/public/qr_codes/' . $filename));
        $text = $qrReader->text();  // El texto del código QR

        if (!$text) {
            // Si no se encuentra un QR, eliminar el archivo y retornar un mensaje de error
            unlink(storage_path('app/public/qr_codes/' . $filename)); // Eliminar archivo
            return redirect()->back()->with('error', 'La imagen no contiene un código QR válido.');
        }

        $package->qr_code = $filename; // Asigna el nombre del archivo a la propiedad
    }

    // Guardar el paquete
    $package->save();

    return redirect()->route('packages.index')->with('success', 'Paquete creado exitosamente.');
}


    public function edit($id)
    {
        $package = Package::findOrFail($id);
        $parkings = Parking::where('user_id', Auth::id())->get(); // Obtener los parqueos del usuario autenticado

        return view('livewire.packages.edit', compact('package', 'parkings'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hours' => 'nullable|integer', // Cambiado de tokens a horas
            'price' => 'required|numeric',
            'parking_id' => 'required|exists:parkings,id',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validaciones
        ]);

        $package = Package::findOrFail($id);
        $package->name = $request->input('name');
        $package->hours = $request->input('hours'); // Cambiado de tokens a horas
        $package->price = $request->input('price');
        $package->parking_id = $request->input('parking_id');

        // Manejo de la imagen
        if ($request->hasFile('qr_code')) {
            // Eliminar la imagen anterior si existe
            if ($package->qr_code) {
                Storage::disk('public')->delete('qr_codes/' . $package->qr_code);
            }

            $file = $request->file('qr_code');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('qr_codes', $filename, 'public'); // Guarda la imagen en storage/app/public/qr_codes
            $package->qr_code = $filename; // Asigna el nombre del archivo a la propiedad
        }

        $package->save();

        return redirect()->route('packages.index')->with('success', 'Paquete actualizado exitosamente.');
    }

    public function destroy(Request $request, $id)
{
    $package = Package::findOrFail($id);

    // Verificar si se ha enviado un 'status' para habilitar o deshabilitar
    if ($request->has('status')) {
        // Cambiar el estado del paquete
        $package->status = $request->input('status');
        $package->save();

        return redirect()->route('packages.index')->with('success', 'Estado del paquete actualizado correctamente.');
    }

    // Si no se proporciona 'status', eliminar el paquete
    $package->delete();

    return redirect()->route('packages.index')->with('success', 'Paquete eliminado exitosamente.');
}

}
