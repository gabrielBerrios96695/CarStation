@extends('layouts.app')

@section('breadcrumbs')
    Gestión de Plazas de Parqueo
@endsection

@section('content')
<div class="container">
    <h1 class="h3">Selecciona un Parqueo</h1>

    <!-- Formulario para seleccionar un parqueo -->
    <form method="GET" action="{{ route('parkings.view') }}">
        <div class="form-group">
            <label for="parking_id">Parqueo</label>
            <select name="parking_id" id="parking_id" class="form-control" onchange="this.form.submit()">
                <option value="">Selecciona un parqueo</option>
                @foreach ($parkings as $parking)
                    <option value="{{ $parking->id }}" {{ $selectedParking && $selectedParking->id == $parking->id ? 'selected' : '' }}>
                        {{ $parking->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <!-- Filtro por hora -->
    <form method="GET" action="{{ route('parkings.view') }}">
        <div class="form-group">
            <label for="start_time">Hora de inicio</label>
            <input type="datetime-local" name="start_time" id="start_time" class="form-control" value="{{ request('start_time') }}">
        </div>
        <div class="form-group">
            <label for="end_time">Hora de fin</label>
            <input type="datetime-local" name="end_time" id="end_time" class="form-control" value="{{ request('end_time') }}">
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </form>

    @if ($selectedParking)
        <h2 class="h4 mt-4">Plazas en {{ $selectedParking->name }}</h2>
        <div class="row">
            @php
            $cont = 1;
            @endphp
            @foreach ($plazas as $plaza)
                <div class="col-md-2 mb-3">
                    <div 
                        class="p-3 text-white text-center" 
                        style="border-radius: 5px; background-color: {{ $plaza->isReserved ? 'red' : 'green' }};"
                        data-bs-toggle="modal" 
                        data-bs-target="#modalReserva{{ $plaza->id }}">
                        Plaza {{ $cont }}
                    </div>

                    <!-- Modal para reserva -->
                    <div class="modal fade" id="modalReserva{{ $plaza->id }}" tabindex="-1" aria-labelledby="modalReservaLabel{{ $plaza->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalReservaLabel{{ $plaza->id }}">Reservar Plaza {{ $cont }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @if (!$plaza->isReserved)
                                        <form action="{{ route('reservations.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="plaza_id" value="{{ $plaza->id }}">
                                            <input type="hidden" name="parking_id" value="{{ $selectedParking->id }}">
                                            
                                            <div class="mb-3">
                                                <label for="start_time" class="form-label">Hora de inicio</label>
                                                <input type="datetime-local" name="start_time" id="start_time" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label for="end_time" class="form-label">Hora de fin</label>
                                                <input type="datetime-local" name="end_time" id="end_time" class="form-control">
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary">Reservar</button>
                                        </form>
                                    @else
                                        <p>La plaza ya está reservada.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                $cont++;
                @endphp
            @endforeach
        </div>
    @endif
</div>
@endsection
