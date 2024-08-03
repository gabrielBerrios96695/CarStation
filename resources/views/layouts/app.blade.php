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
</head>
<body>
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
                            <i class="fas fa-users"></i>
                            <span class="mx-3">Usuarios</span>
                        </a>
                        <a class="flex items-center mt-4 py-2 px-6" href="">
                            <i class="fas fa-store"></i>
                            <span class="mx-3">Tiendas</span>
                        </a>
            
                        
             
            </nav>
            <div class="absolute bottom-0 w-full p-4 bg-yellow-300">
                <div class="flex items-center">
                    <i class="fas fa-user-circle"></i>
                    <span class="mx-3">{{ Auth::user()->name }}</span>
                </div>
            </div>
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
                        {{ Auth::user()->name }}
                    </button>
                    <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10">
                        <a class="block px-4 py-2 text-sm hover:bg-gray-200">Perfil</a>
                        <form method="POST" ">
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
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>