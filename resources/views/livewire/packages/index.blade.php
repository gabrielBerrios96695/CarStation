@extends('layouts.app')

@section('breadcrumbs')
    Gestión de Paquetes
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Paquetes</h1>
        <div>
            <a href="{{ route('packages.export') }}" class="btn btn-success mr-2">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <!-- Botón para crear un nuevo paquete -->
            <a href="{{ route('packages.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Crear Paquete
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-box"></i> Paquetes de Estacionamientos
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="thead bg-blue-300">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre del Paquete</th>
                        <th scope="col">Qr</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Horas</th> <!-- Cambiado de Tokens a Horas -->
                        <th scope="col">Estacionamiento</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($packages as $package)
                        <tr>
                            <th scope="row">{{ $package->id }}</th>
                            <td>{{ $package->name }}</td>
                            <td>
                                <img src="{{ asset('storage/qr_codes/' . $package->qr_code) }}" alt="QR Code" class="qr-image">
                            </td>
                            <td>{{ number_format($package->price, 2) }}Bs</td>
                            <td>{{ $package->hours ?? 'N/A' }}</td> <!-- Cambiado de tokens a hours -->
                            <td>{{ $package->parking->name }}</td>
                            <td>
                                <!-- Botón para editar el paquete -->
                                <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Formulario para eliminar el paquete -->
                                <form action="{{ route('packages.destroy', $package->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
