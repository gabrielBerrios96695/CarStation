<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParkingController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');
        
        $parkings = Parking::orderBy($sortField, $sortDirection)->get();

        return view('livewire.parkings.index', compact('parkings', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        return view('livewire.parkings.create');
    }

    public function edit(Parking $parking)
    {
        return view('livewire.parkings.edit', compact('parking'));
    }

    public function update(Request $request, Parking $parking)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'capacity' => 'required|integer|min:0',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
        ]);

        $parking->update([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'capacity' => $request->capacity,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
        ]);

        return redirect()->route('parkings.index')->with('success', 'Estacionamiento actualizado correctamente');
    }

    public function destroy(Parking $parking)
    {
        $parking->update(['status' => 0]);

        return redirect()->route('parkings.index')->with('success', 'Estacionamiento eliminado correctamente');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'capacity' => 'required|integer|min:0',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
        ]);

        Parking::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'capacity' => $request->capacity,
            'status' => 1, // Activo por defecto
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
        ]);

        return redirect()->route('parkings.index')->with('success', 'Estacionamiento creado exitosamente.');
    }

    public function toggleStatus(Parking $parking)
    {
        $parking->status = !$parking->status;
        $parking->save();

        return redirect()->route('parkings.index')->with('success', 'Estado del estacionamiento actualizado correctamente');
    }
}
