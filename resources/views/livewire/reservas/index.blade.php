@extends('layouts.app')

@section('breadcrumbs')
    Gestión de Reservas
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Lista de Reservas</h1>
        
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-parking"></i> Reservas
        </div>
        <div class="card-body">
            <!-- Selector para cambiar de parqueo -->
            <form method="GET" action="{{ route('reservas.index') }}" class="mb-4">
                <div class="form-group">
                    <label for="parking_id">Selecciona un Parqueo:</label>
                    <select name="parking_id" id="parking_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Seleccione un parqueo...</option>
                        @foreach($parkings as $parking)
                            <option value="{{ $parking->id }}" {{ request('parking_id') == $parking->id ? 'selected' : '' }}>
                                {{ $parking->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            @if($reservations->isEmpty())
                <div class="alert alert-info">No hay reservas disponibles para este parqueo.</div>
            @else
                <table class="table table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Plaza</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Fecha de Reserva</th>
                            <th scope="col">Hora de Inicio</th>
                            <th scope="col">Hora de Fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                            <tr>
                                <th scope="row">{{ $reservation->id }}</th>
                                <td>{{ $reservation->plaza->id }}</td>
                                <td>{{ $reservation->user->name }}</td>
                                <td>{{ $reservation->reservation_date }}</td>
                                <td>{{ $reservation->start_time }}:00</td>
                                <td>{{ $reservation->end_time }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<!-- Modal para confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar esta reserva?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var reservationId = button.data('id');
            var action = '{{ route("reservas.destroy", ":id") }}';
            action = action.replace(':id', reservationId);
            $('#deleteForm').attr('action', action);
        });
    </script>
@endpush
