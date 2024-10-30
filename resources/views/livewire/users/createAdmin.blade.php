@extends('layouts.app')

@section('breadcrumbs')
    Registrar Administrador
@endsection

@section('content')
<div class="container">
    <h1 class="h3 my-4">Registrar Nuevo Administrador</h1>
    <form action="{{ route('users.storeAdmin') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Correo</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="phone_number">Teléfono</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control">
        </div>
        <div class="form-group">
            <label for="address">Dirección</label>
            <input type="text" name="address" id="address" class="form-control">
        </div>
        <div class="form-group">
            <label for="ci">Documento de Identidad (CI)</label>
            <input type="text" name="ci" id="ci" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Registrar Administrador</button>
    </form>
</div>
@endsection
