@extends('layouts.app')

@section('breadcrumbs')
    Gestión de Usuarios
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Editar Usuario</h1>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-user-edit"></i> Formulario de Edición de Usuario
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>¡Ups!</strong> Hay algunos problemas con los datos que has ingresado:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
                @csrf
                @method('PUT')

                <!-- Nombre -->
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Apellido Paterno -->
                <div class="form-group">
                    <label for="first_lastname">Apellido Paterno</label>
                    <input type="text" name="first_lastname" id="first_lastname" value="{{ old('first_lastname', $user->first_lastname) }}" class="form-control @error('first_lastname') is-invalid @enderror" required>
                    @error('first_lastname')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Apellido Materno -->
                <div class="form-group">
                    <label for="second_lastname">Apellido Materno</label>
                    <input type="text" name="second_lastname" id="second_lastname" value="{{ old('second_lastname', $user->second_lastname) }}" class="form-control @error('second_lastname') is-invalid @enderror">
                    @error('second_lastname')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Correo Electrónico -->
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div class="form-group">
                    <label for="phone_number">Teléfono</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="form-control @error('phone_number') is-invalid @enderror" required>
                    @error('phone_number')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- CI -->
                <div class="form-group">
                    <label for="ci">Cédula de Identidad (CI)</label>
                    <input type="text" name="ci" id="ci" value="{{ old('ci', $user->ci) }}" class="form-control @error('ci') is-invalid @enderror" required>
                    @error('ci')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Rol -->
                <div class="form-group">
                    <label for="role">Rol</label>
                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="" disabled>Seleccionar rol</option>
                        <option value="1" {{ old('role', $user->role) == '1' ? 'selected' : '' }}>Administrador</option>
                        <option value="2" {{ old('role', $user->role) == '2' ? 'selected' : '' }}>Dueño de Parqueo</option>
                        <option value="3" {{ old('role', $user->role) == '3' ? 'selected' : '' }}>Cliente</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Dirección (solo si el rol es Dueño de Parqueo) -->
                <div class="form-group" id="address_group" style="display: none;">
                    <label for="address">Dirección</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}" class="form-control @error('address') is-invalid @enderror">
                    @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Imagen del CI (solo si el rol es Dueño de Parqueo) -->
                <div class="form-group" id="ci_image_group" style="display: none;">
                    <label for="ci_image">Imagen del CI (solo para Dueño de Parqueo)</label>
                    <input type="file" name="ci_image" id="ci_image" class="form-control-file" onchange="previewImage(event)">
                    @if ($user->ci_image)
                        <br><img src="{{ asset('storage/ci_images/' . $user->ci_image) }}" alt="Imagen del CI" style="max-width: 200px;">
                    @endif
                    <br>
                    <img id="ci_image_preview" src="" alt="Vista previa de la imagen" style="max-width: 30%; display: none;"/>
                    @error('ci_image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Botón para mostrar el modal de confirmación -->
                <button type="button" class="btn btn-primary mt-4" onclick="confirmSubmit()">Confirmar cambios</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Mostrar el campo de dirección y la imagen del CI solo si el rol es Dueño de Parqueo
    document.getElementById('role').addEventListener('change', function() {
        var addressGroup = document.getElementById('address_group');
        var ciImageGroup = document.getElementById('ci_image_group');
        var role = this.value;

        if (role == '2') { // Si el rol es Dueño de Parqueo
            addressGroup.style.display = 'block';
            ciImageGroup.style.display = 'block';
        } else {
            addressGroup.style.display = 'none';
            ciImageGroup.style.display = 'none';
        }
    });

    // Función para mostrar la vista previa de la imagen del CI
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('ci_image_preview');
            output.style.display = 'block'; // Mostrar la imagen
            output.src = reader.result; // Establecer la imagen
        }
        reader.readAsDataURL(event.target.files[0]); // Leer el archivo de imagen
    }

    // Función para mostrar el modal de confirmación
    function confirmSubmit() {
        // Mostrar SweetAlert
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Revisa los datos antes de enviar el formulario",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('editForm').submit(); // Enviar el formulario
            }
        });
    }

    // Ejecutar la lógica de mostrar/ocultar campos cuando la página carga
    document.addEventListener('DOMContentLoaded', function() {
        var role = document.getElementById('role').value;
        if (role == '2') {
            document.getElementById('address_group').style.display = 'block';
            document.getElementById('ci_image_group').style.display = 'block';
        }
    });
</script>

@endsection
