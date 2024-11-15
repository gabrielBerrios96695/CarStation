@extends('layouts.app')

@section('breadcrumbs')
   Gestión de Usuarios
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Registrar Nuevo Usuario</h1>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-user-plus"></i> Formulario de Registro
        </div>
        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: '¡Ups!',
                    html: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    confirmButtonText: 'Aceptar'
                });
            </script>
        @endif


        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nombre -->
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <!-- Apellido Paterno -->
                <div class="form-group">
                    <label for="first_lastname">Apellido Paterno</label>
                    <input type="text" name="first_lastname" id="first_lastname" class="form-control" value="{{ old('first_lastname') }}" required>
                </div>

                <!-- Apellido Materno -->
                <div class="form-group">
                    <label for="second_lastname">Apellido Materno (opcional)</label>
                    <input type="text" name="second_lastname" id="second_lastname" class="form-control" value="{{ old('second_lastname') }}">
                </div>

                <!-- Correo Electrónico -->
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <!-- Número de Teléfono -->
                <div class="form-group">
                    <label for="phone_number">Número de Teléfono</label>
                    <input type="number" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}" required>
                </div>

                

                <!-- Rol -->
                <div class="form-group">
                    <label for="role">Rol</label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="" disabled selected>Seleccionar rol</option>
                        <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>Administrador</option>
                        <option value="2" {{ old('role') == '2' ? 'selected' : '' }}>Dueño de Parqueo</option>
                        <option value="3" {{ old('role') == '3' ? 'selected' : '' }}>Cliente</option>
                    </select>
                </div>

                <!-- CI -->
                <div class="form-group">
                    <label for="ci">Número de CI</label>
                    <input type="text" name="ci" id="ci" class="form-control" value="{{ old('ci') }}" required>
                </div>

                <div class="form-group" id="ci_image_group" style="display:none;">
                    <label for="ci_image">Imagen del CI (solo para Dueño de Parqueo)</label>
                    <input type="file" name="ci_image" id="ci_image" class="form-control-file" onchange="previewImage(event)">
                    <br>
                    <!-- Contenedor para mostrar la vista previa de la imagen -->
                    <img id="ci_image_preview" src="" alt="Vista previa de la imagen" style="max-width: 30%; display: none;"/>
                </div>
                <!-- Dirección, solo se muestra si el rol es "Dueño de Parqueo" -->
                <div class="form-group" id="address_group" style="display:none;">
                    <label for="address">Dirección</label>
                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
                </div>

                <!-- Contraseña -->
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <!-- Confirmar Contraseña -->
                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Registrar</button>
            </form>
        </div>
    </div>
</div>

<!-- Script para mostrar el campo ci_image y address solo si se selecciona "Dueño de Parqueo" -->
<script>
    document.getElementById('role').addEventListener('change', function() {
        var ciImageGroup = document.getElementById('ci_image_group');
        var addressGroup = document.getElementById('address_group');
        var role = this.value;

        if (role == '2') {
            // Si se selecciona "Dueño de Parqueo", mostrar el campo para la imagen del CI y dirección
            ciImageGroup.style.display = 'block';
            addressGroup.style.display = 'block';
        } else {
            // Si no, ocultar los campos
            ciImageGroup.style.display = 'none';
            addressGroup.style.display = 'none';
        }
    });
     // Función para mostrar la vista previa de la imagen seleccionada
     function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('ci_image_preview');
            output.style.display = 'block'; // Mostrar la imagen
            output.src = reader.result; // Establecer la imagen
        }
        reader.readAsDataURL(event.target.files[0]); // Leer el archivo de imagen
    }
</script>

@endsection
