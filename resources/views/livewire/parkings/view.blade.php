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

    @if ($selectedParking)
        <h2 class="h4 mt-4">Plazas en {{ $selectedParking->name }}</h2>
        <div class="row">
            @php
            $cont=1;
            @endphp
            @foreach ($plazas as $plaza)
                <div class="col-md-2 mb-3">
                    <div class="p-3 bg-success text-white text-center" style="border-radius: 5px;">
                        Plaza {{ $cont}}
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
