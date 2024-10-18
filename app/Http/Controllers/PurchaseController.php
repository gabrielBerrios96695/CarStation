<?php
namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // Método para mostrar la lista de compras
    public function index()
    {
        // Recuperar todas las compras junto con el usuario y el paquete asociado
        $purchases = Purchase::with(['user', 'package'])->get();

        // Pasar las compras a la vista
        return view('livewire.purchases.index', compact('purchases'));
    }
    public function create()
    {
        $packages = Package::all(); // Cargar todos los paquetes
        $users = User::where('role', 3)->get(); // Cargar usuarios con rol 3

        return view('livewire.purchases.create', compact('packages', 'users'));
    }

    public function store(Request $request, $packageId)
{
    // Definición de reglas de validación
    $rules = [
        'user_id' => 'required|integer|exists:users,id',
        'amount' => 'required|numeric|min:0', // Asegúrate de que el monto sea positivo
        'description' => 'required|string|in:Efectivo,QR', // Métodos de pago permitidos
    ];

    // Mensajes de error personalizados
    $messages = [
        'user_id.required' => 'El campo cliente es obligatorio.',
        'user_id.exists' => 'El cliente seleccionado no es válido.',
        'amount.required' => 'El campo monto es obligatorio.',
        'amount.numeric' => 'El campo monto debe ser un número.',
        'amount.min' => 'El monto debe ser mayor que cero.',
        'description.required' => 'El campo método de pago es obligatorio.',
        'description.in' => 'El método de pago debe ser "Efectivo" o "QR".',
    ];

    // Validar los datos de entrada
    $validatedData = $request->validate($rules, $messages);

    try {
        // Lógica para crear la compra
        $purchase = new Purchase();
        $purchase->user_id = $validatedData['user_id'];
        $purchase->package_id = $packageId; // Asegúrate de que este ID sea válido
        $purchase->amount = $validatedData['amount'];
        $purchase->description = $validatedData['description'];
        $purchase->save();

        return redirect()->route('purchases.index')->with('success', 'Compra registrada correctamente.');
    } catch (\Exception $e) {
        // Manejo de errores
        return back()->withErrors(['error' => 'Ocurrió un error al procesar la compra: ' . $e->getMessage()]);
    }
}


}

