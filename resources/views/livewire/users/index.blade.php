@extends('layouts.app')

@section('breadcrumbs')
    Gestión de Usuarios
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Usuarios</h1>
        <div>
            <a href="{{ route('users.export') }}" class="btn btn-success mr-2">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Registrar Nuevo Usuario
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-users"></i> Usuarios
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <th scope="row">{{ $user->id }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role == 1 ? 'Administrador' : ($user->role == 2 ? 'Usuario' : 'Cliente') }}</td>
                            <td>
                                <span class="badge {{ $user->status ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $user->status ? 'Activo' : 'Eliminado' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                @if ($user->status != 0)
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="{{ $user->id }}">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                @else
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#restoreModal" data-id="{{ $user->id }}">
                                        <i class="fas fa-undo"></i> Restaurar
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modales de eliminación y restauración van aquí -->


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
                ¿Estás seguro de que deseas cambiar el estado de este usuario?
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
            var userId = button.data('id');
            var action = '{{ route("users.toggleStatus", ":id") }}';
            action = action.replace(':id', userId);
            $('#toggleStatusForm').attr('action', action);
        });
    </script>
@endpush
