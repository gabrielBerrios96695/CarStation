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

    <!-- Buscador -->
    <div class="mb-3">
        <input type="text" id="search" class="form-control" placeholder="Buscar por nombre completo">
    </div>

    <!-- Tabla de usuarios -->
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
                        <th scope="col">Teléfono</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody id="userTable">
                    @foreach ($users as $index => $user)
                        <tr class="userRow">
                            <!-- Muestra el índice según la paginación -->
                            <th scope="row">{{ $users->firstItem() + $index }}</th>
                            <td>{{ $user->name }} {{ $user->first_lastname }} {{ $user->second_lastname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_number }}</td>
                            <td>
                                @if ($user->role == 1)
                                    Administrador
                                @elseif ($user->role == 2)
                                    Dueño de Parqueo
                                @elseif ($user->role == 3)
                                    Cliente
                                @else
                                    Desconocido
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" 
                                        onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }} {{ $user->first_lastname }} {{ $user->second_lastname }}')">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
            {{ $users->links() }} <!-- Paginación -->
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(userId, userName) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas eliminar al usuario: ${userName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si confirma la eliminación, se redirige al método destroy con el ID del usuario
                window.location.href = `/users/${userId}/destroy`;
            }
        });
    }

    // Filtro dinámico
    document.getElementById('search').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.userRow');
        
        rows.forEach(row => {
            let name = row.querySelector('td').textContent.toLowerCase();
            if (name.indexOf(filter) > -1) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

@endsection
