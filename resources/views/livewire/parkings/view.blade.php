@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">{{ $parking->name }}</h1>
    @if(session('ticket_url'))
    <div class="alert alert-info">
        <a href="{{ session('ticket_url') }}" class="btn btn-primary" target="_blank">Descargar Ticket</a>
    </div>
@endif
    <!-- Formulario para seleccionar la fecha de reserva -->
    <form method="GET" action="{{ route('parkings.view', ['id' => $parking->id]) }}" class="mb-4">
        <label for="reservation_date">Selecciona una fecha:</label>
        <input type="date" name="reservation_date" id="reservation_date" value="{{ $reservationDate }}">
        <button type="submit" class="btn btn-primary">Buscar horas disponibles</button>
    </form>

    <h2 class="mb-3">Plazas Disponibles</h2>
    <div class="row">
        @foreach($plazas as $plaza)
            @php
                $availableHours = $available_hours_by_plaza[$plaza->id] ?? [];
                $hasAvailableHours = !empty($availableHours);
                $colorClass = $hasAvailableHours ? 'bg-success' : 'bg-danger';
            @endphp

            <div class="col-md-4 mb-3">
                <div class="card {{ $colorClass }} text-white">
                    <div class="card-header">Plaza ID: {{ $plaza->id }}</div>
                    <div class="card-body">
                        <p>Estado: {{ $plaza->status == 1 ? 'Activa' : 'Inactiva' }}</p>
                        @if ($hasAvailableHours)
                            <button class="btn btn-light" data-toggle="modal" data-target="#modal{{ $plaza->id }}">
                                Reservar Horas
                            </button>
                        @else
                            <p>No hay horas disponibles para reservar.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal para seleccionar rango de horas -->
            <div class="modal fade" id="modal{{ $plaza->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $plaza->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel{{ $plaza->id }}">Seleccionar Rango de Horas para Plaza ID: {{ $plaza->id }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('reservar', ['plaza_id' => $plaza->id]) }}" method="POST" id="form{{ $plaza->id }}">
                                @csrf
                                <input type="hidden" name="reservation_date" value="{{ $reservationDate }}">
                                
                                <!-- Desplegable para seleccionar el paquete comprado -->
                                <div class="form-group">
                                    <label for="package_id">Selecciona un Paquete:</label>
                                    <select name="package_id" id="package_id{{ $plaza->id }}" class="form-control">
                                        <option value="">Seleccione...</option>
                                        @if($purchasedPackages->isEmpty())
                                            <option value="" disabled>Sin paquetes disponibles. Por favor, compre otro paquete.</option>
                                        @else
                                            @foreach($purchasedPackages as $purchase)
                                                <option value="{{ $purchase->package->id }}">
                                                    {{ $purchase->package->name }} - {{ $purchase->hours }} horas
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="start_time">Hora de Inicio:</label>
                                    <select name="start_time" id="start_time{{ $plaza->id }}" class="form-control">
                                        <option value="">Seleccione...</option>
                                        @foreach($availableHours as $hour)
                                            <option value="{{ $hour }}">{{ $hour }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="end_time">Hora de Fin:</label>
                                    <select name="end_time" id="end_time{{ $plaza->id }}" class="form-control" disabled>
                                        <option value="">Seleccione...</option>
                                    </select>
                                </div>
                                <div id="error-message-{{ $plaza->id }}" class="text-danger"></div>
                                <button type="button" class="btn btn-primary" id="submit{{ $plaza->id }}" disabled onclick="validateForm({{ $plaza->id }})">Reservar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Script para manejar la selección de horas y habilitación del botón -->
            <script>
                document.getElementById('start_time{{ $plaza->id }}').addEventListener('change', function() {
                    let startHour = this.value;
                    let endHourSelect = document.getElementById('end_time{{ $plaza->id }}');
                    let submitButton = document.getElementById('submit{{ $plaza->id }}');
                    let errorMessage = document.getElementById('error-message-{{ $plaza->id }}');

                    endHourSelect.innerHTML = '<option value="">Seleccione...</option>';
                    endHourSelect.disabled = true;
                    errorMessage.innerHTML = ''; // Limpiar mensaje de error

                    // Obtener las horas disponibles para la plaza actual
                    let availableHours = @json($available_hours_by_plaza[$plaza->id]);
                    let startIndex = availableHours.indexOf(startHour);

                    // Validar horas consecutivas a partir de la hora de inicio seleccionada
                    if (startIndex !== -1) {
                        for (let i = startIndex + 1; i < availableHours.length; i++) {
                            endHourSelect.innerHTML += `<option value="${availableHours[i]}">${availableHours[i]}</option>`;
                        }
                        endHourSelect.disabled = false;
                    }

                    submitButton.disabled = true;
                    endHourSelect.addEventListener('change', function() {
                        submitButton.disabled = this.value === '' || document.getElementById('package_id' + {{ $plaza->id }}).value === '';
                    });
                });

                document.getElementById('package_id{{ $plaza->id }}').addEventListener('change', function() {
                    document.getElementById('submit{{ $plaza->id }}').disabled = this.value === '' || document.getElementById('end_time{{ $plaza->id }}').value === '';
                });

                function validateForm(plazaId) {
                    let startHour = document.getElementById('start_time' + plazaId).value;
                    let endHour = document.getElementById('end_time' + plazaId).value;
                    let packageId = document.getElementById('package_id' + plazaId).value;
                    let errorMessage = document.getElementById('error-message-' + plazaId);

                    if (startHour === '' || endHour === '' || packageId === '') {
                        errorMessage.innerHTML = 'Seleccione tanto la hora de inicio como la de fin y el paquete.';
                        return false;
                    }

                    if (endHour <= startHour) {
                        errorMessage.innerHTML = 'La hora de fin debe ser posterior a la hora de inicio.';
                        return false;
                    }

                    // Validar que todas las horas entre el rango seleccionado estén disponibles
                    let availableHours = @json($available_hours_by_plaza[$plaza->id]);
                    let startIndex = availableHours.indexOf(startHour);
                    let endIndex = availableHours.indexOf(endHour);

                    // Asegurarse de que el índice de la hora de fin sea mayor que el de inicio
                    if (endIndex <= startIndex) {
                        errorMessage.innerHTML = 'La hora de fin debe ser posterior a la hora de inicio.';
                        return false;
                    }

                    // Comprobar si todas las horas entre startHour y endHour están disponibles
                    for (let i = startIndex + 1; i <= endIndex; i++) {
                        if (availableHours[i] !== availableHours[startIndex + (i - startIndex)]) {
                            errorMessage.innerHTML = 'Las horas seleccionadas deben ser consecutivas y estar disponibles.';
                            return false;
                        }
                    }

                    document.getElementById('form' + plazaId).submit();
                }
            </script>
        @endforeach
    </div>
</div>
@endsection
