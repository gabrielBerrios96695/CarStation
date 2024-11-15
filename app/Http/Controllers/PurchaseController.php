<?php
namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Parking;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index()
{
    // Obtener el usuario autenticado
    $user = auth()->user();

    // Si el rol del usuario es 3, filtrar solo las compras relacionadas a él
    if ($user->role == 3) {
        $purchases = Purchase::with(['user', 'package'])
                             ->where('user_id', $user->id) // Filtrar por el ID del usuario
                             ->get();
    }
    // Si el rol del usuario es 2, filtrar solo las compras relacionadas a los parqueos registrados a su nombre
    elseif ($user->role == 2) {
        // Obtener los parqueos registrados a nombre del usuario
        $parkings = Parking::where('user_id', $user->id)->pluck('id'); // Obtener los IDs de los parqueos

        $purchases = Purchase::with(['user', 'package'])
                             ->whereIn('package_id', function ($query) use ($parkings) {
                                 $query->select('id')->from('packages')->whereIn('parking_id', $parkings);
                             })
                             ->get();
    }
    // Si el usuario tiene otro rol, obtener todas las compras
    else {
        $purchases = Purchase::with(['user', 'package'])->get();
    }

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


    $rules = [
        'user_id' => 'required|integer|exists:users,id',
        'amount' => 'required|numeric|min:0', // Asegúrate de que el monto sea positivo
        'description' => 'required|string|in:Efectivo,QR', // Métodos de pago permitidos
        'hours' => 'required|integer|min:1', // Validación para las horas
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
        'hours.required' => 'El campo horas es obligatorio.',
        'hours.integer' => 'El campo horas debe ser un número entero.',
        'hours.min' => 'El número de horas debe ser al menos 1.',
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
        $purchase->hours = $request->input('hours'); // Asigna las horas compradas
        $purchase->hours_purchases = $request->input('hours');
        $purchase->save();

        return redirect()->route('purchases.index')->with('success', 'Compra registrada correctamente.');
    } catch (\Exception $e) {
        // Manejo de errores
        return back()->withErrors(['error' => 'Ocurrió un error al procesar la compra: ' . $e->getMessage()]);
    }
}



}

