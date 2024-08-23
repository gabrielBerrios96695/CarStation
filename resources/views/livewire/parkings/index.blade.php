@extends('layouts.app')

@section('breadcrumbs')
    Gestion de Garajes
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Estacionamientos</h1>
        <a href="{{ route('parkings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Agregar Nuevo Estacionamiento
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-parking"></i> Estacionamientos
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="thead bg-blue-300">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Latitud</th>
                        <th scope="col">Longitud</th>
                        <th scope="col">Capacidad</th>
                        <th scope="col">Hora de Apertura</th>
                        <th scope="col">Hora de Cierre</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($parkings as $parking)
                        <tr>
                            <th scope="row">{{ $parking->id }}</th>
                            <td>{{ $parking->name }}</td>
                            <td>{{ $parking->latitude }}</td>
                            <td>{{ $parking->longitude }}</td>
                            <td>{{ $parking->capacity }}</td>
                            <td>{{ $parking->opening_time }}</td>
                            <td>{{ $parking->closing_time }}</td>
                            <td>
                                <span class="badge {{ $parking->status ? 'badge-success' : 'badge-danger' }}">
                                    {{ $parking->status ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('parkings.edit', $parking->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i> 
                                </a>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#toggleStatusModal" data-id="{{ $parking->id }}" data-status="{{ $parking->status }}">
                                    <i class="fas fa-toggle-on"></i> 
                                </button>
                                <form action="{{ route('parkings.destroy', $parking->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background-color: black; color: white;">
                                            <i class="fas fa-trash-alt"></i>
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

<!-- Modal para cambiar el estado -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toggleStatusModalLabel">Confirmar Cambio de Estado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas cambiar el estado de este estacionamiento?
            </div>
            <div class="modal-footer">
                <form id="toggleStatusForm" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $('#toggleStatusModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var parkingId = button.data('id');
            var parkingStatus = button.data('status');
            var action = '{{ route("parkings.toggleStatus", ":id") }}';
            action = action.replace(':id', parkingId);
            $('#toggleStatusForm').attr('action', action);
        });
    </script>
@endpush
