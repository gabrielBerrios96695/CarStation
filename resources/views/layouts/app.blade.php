<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link href="{{ asset('leaflet/leaflet.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS y dependencias -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>   
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    @if(Auth::check() && Auth::user()->role == 2 && is_null(Auth::user()->password_changed_at))
        <div class="alert alert-warning fixed-top m-4">
            <strong>Atención:</strong> Por favor, cambie su contraseña antes de continuar.
            <a href="{{ route('password.change') }}" class="btn btn-warning btn-sm">Cambiar Contraseña</a>
        </div>
        <main class="main-content flex-1 overflow-x-hidden overflow-y-auto">
                    <div class="container mx-auto px-6 py-8">
                        @yield('content')
                    </div>
                </main>
        
    @else
        <div x-data="{ sidebarOpen: false }" class="flex h-screen">
            <!-- Sidebar -->
            <div :class="sidebarOpen ? 'block' : 'hidden'" class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>
            <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="sidebar fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
                <div class="flex items-center justify-center mt-8">
                    <img src="{{ asset('imagenes/logo.jpeg') }}" alt="Logo" class="h-16 w-auto">
                </div>
                <nav class="mt-10">
                <a class="flex items-center mt-4 py-2 px-6" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="mx-3">Dashboard</span>
                </a>
                <a class="flex items-center mt-4 py-2 px-6" href="{{ route('users.index') }}">
                    <i class="fas fa-users-cog"></i> <!-- Cambiado a usuarios con engranaje -->
                    <span class="mx-3">Usuarios</span>
                </a>
                <a class="flex items-center mt-4 py-2 px-6" href="{{ route('parkings.index') }}">
                    <i class="fas fa-parking"></i> <!-- Cambiado a icono de parking -->
                    <span class="mx-3">Garajes</span>
                </a>
                <a class="flex items-center mt-4 py-2 px-6" href="{{ route('parkings.maps') }}">
                    <i class="fas fa-map-marker-alt"></i> <!-- Cambiado a icono de marcador en mapa -->
                    <span class="mx-3">Buscar Garaje</span>
                </a>
                <a class="flex items-center mt-4 py-2 px-6" href="{{ route('packages.index') }}">
                    <i class="fas fa-box"></i> <!-- Cambiado a icono de paquete -->
                    <span class="mx-3">Paquetes</span>
                </a>
                <a class="flex items-center mt-4 py-2 px-6" href="{{ route('reservas.index') }}">
                    <i class="fas fa-parking"></i> <!-- Icono de parqueo -->
                    <span class="mx-3">Reservas</span>
                </a>

                <a class="flex items-center mt-4 py-2 px-6" href="{{ route('purchases.index') }}">
                    <i class="fas fa-shopping-cart"></i> <!-- Cambiado a icono de carrito de compras -->
                    <span class="mx-3">Ventas</span>
                </a>
                <a class="flex items-center mt-4 py-2 px-6" href="{{ route('reports.clientReservations') }}">
                    <i class="fas fa-calendar-check"></i> <!-- Icono de calendario con check para reportes de reservas -->
                    <span class="mx-3">Reporte de Reservas</span>
                </a>


                   
                </nav>
                
            </div>

            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Navbar -->
                <header class="header flex justify-between items-center py-4 px-6">
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <div class="breadcrumbs">
                            @yield('breadcrumbs')
                        </div>
                    </div>
                    <div x-data="{ open: false }" @click.away="open = false" class="relative user-menu">
                        <button @click="open = !open" class="px-4 py-2 text-sm hover:bg-gray-200">
                            Opciones
                        </button>
                        <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10">
                            <a class="block px-4 py-2 text-sm hover:bg-gray-200">Perfil</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="block px-4 py-2 text-sm hover:bg-gray-200 w-full text-left">
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <main class="main-content flex-1 overflow-x-hidden overflow-y-auto">
                    <div class="container mx-auto px-6 py-8">
                    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    @endif

    @livewireScripts
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    @stack('scripts')

</body>
</html>
