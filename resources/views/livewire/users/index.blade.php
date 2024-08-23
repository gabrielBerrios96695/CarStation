@extends('layouts.app')

@section('breadcrumbs')
    Gestión de Usuarios
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Usuarios</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Registrar Nuevo Usuario
        </a>
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
                            <td>
                                @switch($user->role)
                                    @case(1)
                                        Administrador
                                        @break
                                    @case(2)
                                        Usuario
                                        @break
                                    @case(3)
                                        Cliente
                                        @break
                                    @default
                                        No definido
                                @endswitch
                            </td>
                            <td>
                                @if ($user->status == 0)
                                    <span class="badge badge-secondary">Eliminado</span>
                                @else
                                    <span class="badge badge-success">Activo</span>
                                @endif
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
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
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

<!-- Modal para eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas marcar este usuario como eliminado?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para restauración -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreModalLabel">Confirmar Restauración</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas restaurar este usuario?
            </div>
            <div class="modal-footer">
                <form id="restoreForm" method="POST" class="d-inline">
                    @csrf
                    @method('Post') <!-- Cambiado a PUT -->
                    <button type="submit" class="btn btn-warning">Restaurar</button>
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
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var userId = button.data('id');
            var action = '{{ route("users.destroy", ":id") }}';
            action = action.replace(':id', userId);
            $('#deleteForm').attr('action', action);
        });

        $('#restoreModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var userId = button.data('id');
            var action = '{{ route("users.restore", ":id") }}';
            action = action.replace(':id', userId);
            $('#restoreForm').attr('action', action);
        });
    </script>
@endpush
