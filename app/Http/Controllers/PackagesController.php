<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Parking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PackagesController extends Controller
{
    // Método para mostrar solo los parqueos creados por el usuario autenticado
    public function index()
{
    // Obtener el ID del usuario autenticado
    $userId = Auth::id();

    // Obtener los paquetes relacionados con los parqueos creados por el usuario autenticado
    $packages = Package::whereHas('parking', function ($query) use ($userId) {
        $query->where('created_by', $userId); // Aquí aseguramos que el usuario autenticado es el creador del parqueo
    })->get();

    // Pasar los paquetes a la vista
    return view('livewire.packages.index', compact('packages'));
}
public function create()
{
    // Obtener los parqueos asociados al usuario autenticado
    $userId = auth()->id();
    $parkings = Parking::where('user_id', $userId)->get(); // Asegúrate de que esto retorna una colección

    return view('livewire.packages.create', compact('parkings'));
}


public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'tokens' => 'nullable|integer',
        'price' => 'required|numeric',
        'parking_id' => 'required|exists:parkings,id',
        'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validaciones
    ]);

    $package = new Package();
    $package->name = $request->input('name');
    $package->tokens = $request->input('tokens');
    $package->price = $request->input('price');
    $package->parking_id = $request->input('parking_id');
    $package->created_by = auth()->id();

    // Manejo de la imagen
    if ($request->hasFile('qr_code')) {
        $file = $request->file('qr_code');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('qr_codes', $filename, 'public'); // Guarda la imagen en storage/app/public/qr_codes
        $package->qr_code = $filename; // Asigna el nombre del archivo a la propiedad
    }

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
            'tokens' => 'nullable|integer',
            'price' => 'required|numeric',
            'parking_id' => 'required|exists:parkings,id',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validaciones
        ]);
    
        $package = Package::findOrFail($id);
        $package->name = $request->input('name');
        $package->tokens = $request->input('tokens');
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
    

    // Método para eliminar un paquete
    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return redirect()->route('packages.index')->with('success', 'Paquete eliminado con éxito.');
    }
}
