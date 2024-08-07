@extends('layouts.app')

@section('breadcrumbs')
    Gestion de Garajes
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3">Editar Estacionamiento</h1>
        <a href="{{ route('parkings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la lista
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-parking"></i> Datos del Estacionamiento
        </div>
        <div class="card-body">
            <form action="{{ route('parkings.update', $parking->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="name" class="form-label">Nombre del Estacionamiento</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $parking->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="map" style="height: 300px; width: 100%; margin-top: 20px;"></div>

                <div class="form-group row">
                    <div class="col-md-6 mb-3">
                        <label for="latitude" class="form-label">Latitud</label>
                        <input type="text" id="latitude" name="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $parking->latitude) }}" readonly>
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="longitude" class="form-label">Longitud</label>
                        <input type="text" id="longitude" name="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $parking->longitude) }}" readonly>
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="capacity" class="form-label">Capacidad</label>
                    <input type="number" id="capacity" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', $parking->capacity) }}" required>
                    @error('capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="opening_time" class="form-label">Hora de Apertura</label>
                    <input type="time" id="opening_time" name="opening_time" class="form-control @error('opening_time') is-invalid @enderror" value="{{ old('opening_time', $parking->opening_time) }}" required>
                    @error('opening_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="closing_time" class="form-label">Hora de Cierre</label>
                    <input type="time" id="closing_time" name="closing_time" class="form-control @error('closing_time') is-invalid @enderror" value="{{ old('closing_time', $parking->closing_time) }}" required>
                    @error('closing_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </form>
        </div>
    </div>
</div>

@push('styles')
    <!-- Include Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@push('scripts')
    <!-- Include Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const initialLat = parseFloat(document.getElementById('latitude').value);
            const initialLng = parseFloat(document.getElementById('longitude').value);

            const map = L.map('map').setView([initialLat, initialLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            const icon = L.icon({
                iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            let marker = L.marker([initialLat, initialLng], { icon: icon }).addTo(map);

            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.marker([lat, lng], { icon: icon }).addTo(map);

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            });
        });
    </script>
@endpush
@endsection
