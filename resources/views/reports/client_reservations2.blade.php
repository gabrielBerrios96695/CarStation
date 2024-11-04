@extends('layouts.app')

@section('breadcrumbs')
    Reporte de Usuarios Frecuentes
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Reporte de Usuarios Frecuentes</h1>
        <!-- Exportar a PDF (puedes modificar la ruta y los parámetros según sea necesario) -->
        <a href="{{ route('reports.exportFrequentUsers', ['start_month' => request('start_month'), 'end_month' => request('end_month')]) }}" class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Exportar a PDF
        </a>
    </div>

    <!-- Formulario de filtros -->
    <form method="GET" action="{{ route('reports.clientReservations2') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="start_month">Mes de Inicio:</label>
                <input type="month" name="start_month" id="start_month" class="form-control" value="{{ request('start_month') }}">
            </div>
            <div class="col-md-4">
                <label for="end_month">Mes de Fin:</label>
                <input type="month" name="end_month" id="end_month" class="form-control" value="{{ request('end_month') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Tabla de reportes de usuarios frecuentes -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-users"></i> Usuarios Frecuentes
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Cantidad de Reservas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($frequentUsers as $user)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->reservas_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
