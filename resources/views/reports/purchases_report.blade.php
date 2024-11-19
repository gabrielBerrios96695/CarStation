@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Generar Reporte de Ganancias por Compras</h1>
    
    <!-- Formulario para seleccionar el rango de fechas -->
    <form action="{{ route('report.purchases.report') }}" method="GET">
        @csrf
        <div class="form-row">
            <div class="col-md-4 form-group">
                <label for="start_date">Fecha de inicio</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}" required>
            </div>
            <div class="col-md-4 form-group">
                <label for="end_date">Fecha de fin</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}" required>
            </div>
            <div class="col-md-4 form-group align-self-end">
                <button type="submit" class="btn btn-primary">Generar Reporte</button>
            </div>
        </div>
    </form>

    <!-- Mostrar el reporte solo si hay compras -->
    @if($purchases->isNotEmpty())
        <h4 class="mt-4">Ganancia Total: ${{ number_format($totalRevenue, 2) }}</h4>

        <!-- Botón para generar PDF -->
        <form action="{{ route('report.purchases.generatePDF') }}" method="GET">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <button type="submit" class="btn btn-success mt-4">
                <i class="fas fa-file-pdf"></i> Generar PDF
            </button>
        </form>

        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Descripción</th>
                    <th>Paquete</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->id }}</td>
                        <td>{{ $purchase->user->name }}</td>
                        <td>{{ $purchase->description }}</td>
                        <td>{{ $purchase->package->name }}</td>
                        <td>${{ number_format($purchase->amount, 2) }}</td>
                        <td>{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info mt-4">No se encontraron compras para el rango de fechas seleccionado.</div>
    @endif
</div>
@endsection
