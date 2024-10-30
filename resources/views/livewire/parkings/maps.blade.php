@extends('layouts.app')

@section('breadcrumbs')
    Mapa de Parqueos
@endsection

@section('content')
<div class="container">
    <h1 class="h3">Mapa de Parqueos</h1>

    <!-- Contenedor del mapa -->
    <div id="map" style="height: 600px;"></div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Inicializar el mapa centrado en Cochabamba, Bolivia
        var map = L.map('map').setView([-17.3895, -66.1568], 13);

        // Capa de mapa
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Datos de parqueos desde el backend
        var parkings = @json($parkings);

        // Función para agregar marcadores
        parkings.forEach(function(parking) {
            var color = parking.status === 0 ? 'red' : 'blue'; // Determinar color según estado

            var marker = L.circleMarker([parking.latitude, parking.longitude], {
                radius: 8,
                fillColor: color,
                color: color,
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map);

            // Añadir popup con el nombre del parqueo, conteo de plazas activas y botón "Reservar"
            var popupContent = "<b>" + parking.name + "</b><br>" +
                               "Plazas disponibles: " + parking.plazas_count + "<br>";

            // Solo agregar el botón si el estado no es rojo
            if (parking.status !== 0) {
                popupContent += "<a href='/parkings/view/" + parking.id + "' class='btn btn-primary'>Reservar</a>";
            }

            marker.bindPopup(popupContent);
        });
    </script>
</div>
@endsection
