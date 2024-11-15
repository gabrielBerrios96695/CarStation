@extends('layouts.app')

@section('breadcrumbs')
    Mapa de Parqueos
@endsection

@section('content')
<div class="container">
    <h1 class="h3">Mapa de Parqueos</h1>

    <!-- Campo de búsqueda con Datalist -->
    <div class="mb-3">
        <input type="text" id="parkingSearch" class="form-control" placeholder="Buscar parqueo..." list="parkingList">
        <datalist id="parkingList">
            <!-- Opciones dinámicas para cada parqueo -->
            @foreach ($parkings as $parking)
                <option value="{{ $parking['name'] }}">
            @endforeach
        </datalist>
    </div>

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

        // Objeto para almacenar los marcadores
        var markers = {};

        // Verificar si el usuario autenticado es cliente (role = 3)
        var isClient = @json(auth()->check() && auth()->user()->role == 3);

        // Función para agregar marcadores
        parkings.forEach(function(parking) {
            var color = parking.status === 0 ? 'red' : 'blue'; // Determinar color según estado

            // Crear marcador
            var marker = L.circleMarker([parking.latitude, parking.longitude], {
                radius: 8,
                fillColor: color,
                color: color,
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map);

            // Añadir popup con el nombre del parqueo y conteo de plazas activas
            var popupContent = "<b>" + parking.name + "</b><br>" +
                               "Plazas disponibles: " + parking.plazas_count + "<br>";

            // Mostrar el botón "Reservar" solo si el usuario es cliente y el parqueo está activo
            if (parking.status !== 0 && isClient) {
                popupContent += "<a href='/parkings/view/" + parking.id + "' class='btn btn-primary'>Reservar</a>";
            }

            marker.bindPopup(popupContent);

            // Guardar el marcador en el objeto markers usando el nombre del parqueo como clave
            markers[parking.name.toLowerCase()] = {
                marker: marker,
                latitude: parking.latitude,
                longitude: parking.longitude
            };
        });

        // Evento al seleccionar un parqueo del buscador
        document.getElementById('parkingSearch').addEventListener('input', function(event) {
            var searchText = event.target.value.toLowerCase();

            // Verificar si el nombre del parqueo está en la lista de marcadores
            if (markers[searchText]) {
                var selectedParking = markers[searchText];

                // Centrar el mapa en el marcador del parqueo seleccionado y abrir el popup
                map.setView([selectedParking.latitude, selectedParking.longitude], 16);
                selectedParking.marker.openPopup();
            }
        });
    </script>
</div>
@endsection
