@extends('layouts.app')

@section('breadcrumbs')
    Crear Paquete
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Crear Nuevo Paquete</h1>
        <a href="{{ route('packages.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-box"></i> Formulario de Creación de Paquete
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

            <form action="{{ route('packages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="name">Nombre del Paquete</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">Precio</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tokens">Tokens</label>
                    <input type="number" name="tokens" id="tokens" class="form-control @error('tokens') is-invalid @enderror" value="{{ old('tokens') }}">
                    @error('tokens')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="parking_id">Estacionamiento</label>
                    <select name="parking_id" id="parking_id" class="form-control @error('parking_id') is-invalid @enderror" required>
                        <option value="" disabled selected>Seleccionar estacionamiento</option>
                        @foreach ($parkings as $parking)
                            <option value="{{ $parking->id }}" {{ old('parking_id') == $parking->id ? 'selected' : '' }}>{{ $parking->name }}</option>
                        @endforeach
                    </select>
                    @error('parking_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="qr_code">Código QR</label>
                    <input type="file" name="qr_code" id="qr_code" class="form-control @error('qr_code') is-invalid @enderror">
                    @error('qr_code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Crear Paquete</button>
            </form>
        </div>
    </div>
</div>
@endsection
