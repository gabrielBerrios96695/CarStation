@extends('layouts.app')

@section('breadcrumbs')
    Gestión de Paquetes
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Paquetes</h1>
        <!-- Filtro por estado -->
        <form action="{{ route('packages.index') }}" method="GET" class="d-inline">
            <select id="statusFilter" name="status" class="form-select" onchange="this.form.submit()">
                <!-- Opción para "Inactivo" -->
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivo</option>
                <!-- Opción para "Activo", seleccionado por defecto si no se pasa ningún filtro -->
                <option value="1" {{ request('status') === '1' || !request()->has('status') ? 'selected' : '' }}>Activo</option>
            </select>
        </form>



        <div>
            <a href="{{ route('packages.export') }}" class="btn btn-success mr-2">
                <i class="fas fa-file-excel"></i> Excel
            </a>
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
                        <th scope="col">Horas</th>
                        <th scope="col">Estacionamiento</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody id="packagesTable">
                    @foreach ($packages as $package)
                        <tr class="package-row" data-status="{{ $package->status }}">
                            <th scope="row">{{ $package->id }}</th>
                            <td>{{ $package->name }}</td>
                            <td>
                                <img src="{{ asset('storage/qr_codes/' . $package->qr_code) }}" alt="QR Code" class="qr-image">
                            </td>
                            <td>{{ number_format($package->price, 2) }} Bs</td>
                            <td>{{ $package->hours ?? 'N/A' }}</td>
                            <td>{{ $package->parking->name }}</td>
                            <td>
                                <span class="badge {{ $package->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $package->status == 1 ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('packages.destroy', $package->id) }}" method="POST" class="d-inline" id="status-form-{{ $package->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn {{ $package->status == 1 ? 'btn-warning' : 'btn-success' }}" onclick="toggleStatus({{ $package->id }}, {{ $package->status == 1 ? 0 : 1 }})">
                                        <i class="fas {{ $package->status == 1 ? 'fa-pause' : 'fa-play' }}"></i>
                                        {{ $package->status == 1 ? 'Deshabilitar' : 'Habilitar' }}
                                    </button>
                                </form>

                                <script>
                                    function toggleStatus(packageId, status) {
                                        Swal.fire({
                                            title: '¿Estás seguro?',
                                            text: status === 1 ? '¡Este paquete será habilitado!' : '¡Este paquete será deshabilitado!',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Sí',
                                            cancelButtonText: 'Cancelar',
                                            reverseButtons: true
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                var form = document.getElementById('status-form-' + packageId);
                                                var input = document.createElement('input');
                                                input.type = 'hidden';
                                                input.name = 'status';
                                                input.value = status;
                                                form.appendChild(input);
                                                form.submit();
                                            }
                                        });
                                    }
                                </script>
                                <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-info">
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
