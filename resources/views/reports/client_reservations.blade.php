@extends('layouts.app')

@section('breadcrumbs')
    Reporte de Reservas por Cliente
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Reporte de Reservas por Cliente</h1>
        <a href="{{ route('reports.export', ['parking_id' => request('parking_id'), 'reservation_date' => request('reservation_date')]) }}" class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Exportar a PDF
        </a>
    </div>

    <!-- Formulario de filtros -->
    <form method="GET" action="{{ route('reports.clientReservations') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="parking_id">Seleccionar Parqueo:</label>
                <select name="parking_id" id="parking_id" class="form-control">
                    <option value="">Todos los parqueos</option>
                    @foreach($parkings as $parking)
                        <option value="{{ $parking->id }}" {{ request('parking_id') == $parking->id ? 'selected' : '' }}>
                            {{ $parking->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="reservation_date">Fecha de Reserva:</label>
                <input type="date" name="reservation_date" id="reservation_date" class="form-control" value="{{ request('reservation_date') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Tabla de reportes de reservas -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-calendar-alt"></i> Reservas por Cliente
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Parqueo</th>
                        <th scope="col">Plaza ID</th>
                        <th scope="col">Fecha de Reserva</th>
                        <th scope="col">Hora de Inicio</th>
                        <th scope="col">Hora de Fin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $reservation->user->name }}</td>
                            <td>{{ $reservation->user->email }}</td>
                            <td>{{ $reservation->plaza->parking->name }}</td>
                            <td>{{ $reservation->plaza->id }}</td>
                            <td>{{ $reservation->reservation_date }}</td>
                            <td>{{ $reservation->start_time }}</td>
                            <td>{{ $reservation->end_time }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
