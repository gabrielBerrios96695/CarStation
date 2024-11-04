@extends('layouts.app')

@section('breadcrumbs')
    Gestión de Paquetes
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Editar Paquete</h1>
        <a href="{{ route('packages.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-edit"></i> Formulario de Edición
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

            <form action="{{ route('packages.update', $package->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nombre del Paquete</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $package->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="hours">Horas</label> <!-- Cambiado de Tokens a Horas -->
                    <input type="number" name="hours" id="hours" class="form-control @error('hours') is-invalid @enderror" value="{{ old('hours', $package->hours) }}">
                    @error('hours')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">Precio</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $package->price) }}" required>
                    @error('price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="parking_id">Estacionamiento</label>
                    <select name="parking_id" id="parking_id" class="form-control @error('parking_id') is-invalid @enderror" required>
                        <option value="" disabled>Seleccionar estacionamiento</option>
                        @foreach ($parkings as $parking)
                            <option value="{{ $parking->id }}" {{ old('parking_id', $package->parking_id) == $parking->id ? 'selected' : '' }}>
                                {{ $parking->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parking_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="qr_code">Código QR</label>
                    @if ($package->qr_code)
                        <div class="mb-2">
                            <img src="{{ asset('storage/qr_codes/' . $package->qr_code) }}" alt="Código QR" style="max-width: 150px; max-height: 150px;">
                        </div>
                    @endif
                    <input type="file" name="qr_code" id="qr_code" class="form-control @error('qr_code') is-invalid @enderror">
                    <small class="form-text text-muted">Si deseas cambiar el código QR, sube uno nuevo.</small>
                    @error('qr_code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
</div>
@endsection
