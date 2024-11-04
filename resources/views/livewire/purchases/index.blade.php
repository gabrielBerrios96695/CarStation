@extends('layouts.app')

@section('breadcrumbs')
    Gestión de Compras
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Compras</h1>
        <div>
            <!-- Botón para crear una nueva compra -->
            <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Crear Nueva Compra
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-shopping-cart"></i> Compras Realizadas
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre del Comprador</th>
                        <th scope="col">Paquete Comprado</th>
                        <th scope="col">Horas</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Monto</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                        <tr>
                            <th scope="row">{{ $purchase->id }}</th>
                            <td>{{ $purchase->user->name }}</td>
                            <td>{{ $purchase->package->name }}</td>
                            <td>{{ $purchase->package->hours }}</td>
                            <td>
                                <span class="badge {{ $purchase->status == 1 ? 'badge-warning' : ($purchase->status == 2 ? 'badge-success' : 'badge-danger') }}">
                                    {{ $purchase->status == 1 ? 'Pendiente' : ($purchase->status == 2 ? 'Completa' : 'Cancelada') }}
                                </span>
                            </td>
                            <td>${{ number_format($purchase->amount, 2) }}</td>
                            <td>
                                <!-- Aquí puedes agregar botones para editar o eliminar la compra si es necesario -->
                                <a href="" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
