@extends('layouts.app')

@section('breadcrumbs')
    Gestión de Garajes
@endsection

@section('content')
@php
use App\Models\Plaza;
@endphp
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Estacionamientos</h1>
        <div>
            <a href="{{ route('parkings.export') }}" class="btn btn-success mr-2">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('parkings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Agregar Nuevo Estacionamiento
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-parking"></i> Estacionamientos
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="thead bg-blue-300">
                    <tr>
                        <th scope="col">
                            <a href="{{ route('parkings.index', ['sort_field' => 'id', 'sort_direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                #
                                @if($sortField === 'id')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col">
                            <a href="{{ route('parkings.index', ['sort_field' => 'name', 'sort_direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                Nombre
                                @if($sortField === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col">Latitud</th>
                        <th scope="col">Longitud</th>
                        <th scope="col">
                            <a href="{{ route('parkings.index', ['sort_field' => 'number_of_spaces', 'sort_direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                Número de Plazas
                                @if($sortField === 'number_of_spaces')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
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
                            <td>{{ $parking->plazas_count }}</td>
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
