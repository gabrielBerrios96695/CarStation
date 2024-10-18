<?php
namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // Muestra la lista de paquetes para comprar
    public function index()
    {
        $packages = Package::all();
        return view('purchases.index', compact('packages'));
    }

    // Muestra el formulario para comprar un paquete específico
    public function create($packageId)
    {
        $package = Package::findOrFail($packageId);
        return view('purchases.create', compact('package'));
    }

    // Guarda la compra del paquete
    public function store(Request $request, $packageId)
    {
        $package = Package::findOrFail($packageId);

        // Aquí puedes agregar lógica para el monto y el estado de la compra
        $purchase = new Purchase();
        $purchase->user_id = Auth::id();
        $purchase->package_id = $packageId;
        $purchase->amount = $package->price; // Asignar el precio del paquete
        $purchase->status = 1; // Estado pendiente
        $purchase->save();

        return redirect()->route('purchases.index')->with('success', 'Compra realizada con éxito.');
    }
}

