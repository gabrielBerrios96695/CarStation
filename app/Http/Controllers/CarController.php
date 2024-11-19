<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener los autos registrados por este usuario con status 1 (activos)
        $cars = $user->cars()->where('status', 1)->paginate(5);

        return view('livewire.cars.index', compact('cars'));
    }

    public function store(Request $request)
    {
        // Validaciones
        $validated = $request->validate([
            'model' => 'required|string|regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚ\s\.,-]+$/|max:255', // Solo letras, números, acentos, espacios, puntos, comas y guiones
            'license_plate' => 'required|string|regex:/^[A-Za-z0-9]{6,8}$/|unique:cars,license_plate|max:8', // Placa con letras y números, entre 6 y 8 caracteres
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Imagen requerida
        ], [
            'model.regex' => 'El modelo solo puede contener letras, números, espacios, puntos, comas y guiones.',
            'license_plate.regex' => 'La placa debe contener solo letras y números, y debe tener entre 6 y 8 caracteres.',
            'license_plate.unique' => 'Ya existe un auto con esa placa registrada.',
            'image.required' => 'La imagen del auto es obligatoria.',
            'image.image' => 'La imagen debe ser un archivo de imagen.',
            'image.mimes' => 'La imagen debe ser de tipo jpeg, png, jpg o gif.',
            'image.max' => 'La imagen no debe exceder los 2MB.',
        ]);

        try {
            // Crear el nuevo auto
            $car = new Car();
            $car->model = $request->input('model');
            $car->license_plate = $request->input('license_plate');

            // Manejar la imagen, si se sube
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('cars', 'public');
                $car->image = $imagePath;
            }

            // Asociar el auto al usuario autenticado
            $car->user_id = auth()->id();

            // Guardar el auto
            $car->save();

            // Retornar con mensaje de éxito
            return redirect()->route('cars.index')->with('success', 'Auto registrado exitosamente.');

        } catch (\Exception $e) {
            // Manejar error de la creación
            return back()->withErrors(['error' => 'Hubo un error al registrar el auto. Por favor, intenta nuevamente.'])->withInput();
        }
    }
    public function update(Request $request, Car $car)
    {
        // Validación de los datos
        $validated = $request->validate([
            'model' => 'required|string|max:255',
            'license_plate' => 'required|string|alpha_num|min:6|max:8|unique:cars,license_plate,' . $car->id,  // Validación de la placa
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Actualizar el modelo
        $car->model = $validated['model'];
        $car->license_plate = $validated['license_plate'];

        // Si se ha subido una nueva imagen, procesarla
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($car->image) {
                Storage::delete('public/' . $car->image);
            }

            // Guardar la nueva imagen
            $imagePath = $request->file('image')->store('cars', 'public');
            $car->image = $imagePath;
        }

        // Guardar los cambios en la base de datos
        $car->save();

        // Redirigir o devolver una respuesta
        return redirect()->route('cars.index')->with('success', 'Auto actualizado exitosamente.');
    }
    public function destroy($id)
    {
        // Buscar el auto por su ID
        $car = Car::findOrFail($id);

        // Actualizar el estado del auto a 0 (inactivo o eliminado)
        $car->status = 0;
        $car->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('cars.index')->with('success', 'El auto ha sido marcado como eliminado.');
    }

}
