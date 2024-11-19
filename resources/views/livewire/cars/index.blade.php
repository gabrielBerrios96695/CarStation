@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Mis Autos Registrados</h1>

    <!-- Botón para abrir el modal -->
    <button class="btn btn-primary mb-6" data-bs-toggle="modal" data-bs-target="#createCarModal">
        Crear Auto
    </button>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <!-- Modal de creación -->
    <div class="modal fade" id="createCarModal" tabindex="-1" aria-labelledby="createCarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCarModalLabel">Registrar un Nuevo Auto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="model" class="form-label">Modelo</label>
                            <input type="text" name="model" id="model" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="license_plate" class="form-label">Placa</label>
                            <input type="text" name="license_plate" id="license_plate" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Imagen</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Registrar Auto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($cars->isEmpty())
        <div class="alert alert-info">
            No tienes autos registrados.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($cars as $car)
                <!-- Card para mostrar los autos -->
                <div class="card border border-gray-300 rounded-lg overflow-hidden shadow-lg group">
                    <div class="relative">
                        <!-- Imagen del auto -->
                        <img src="{{ $car->image ? asset('storage/' . $car->image) : 'https://via.placeholder.com/300x200.png?text=Sin+Imagen' }}" alt="Imagen del auto" class="w-full h-48 object-cover">
                        <div class="absolute top-0 left-0 right-0 bottom-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition duration-300 flex justify-center items-center">
                            <span class="text-white text-xl font-bold">{{ $car->license_plate }}</span>
                        </div>
                    </div>

                    <div class="content p-4">
                        <h2 class="text-lg font-semibold">{{ $car->model }}</h2>
                        <p class="text-gray-600">Placa: {{ $car->license_plate }}</p>

                        <div class="mt-4 flex justify-between">
                            <!-- Botón para editar el auto -->
                            <button class="text-yellow-500 hover:text-yellow-700" data-bs-toggle="modal" data-bs-target="#editCarModal{{ $car->id }}">
                                Editar
                            </button>

                            <!-- Formulario para eliminar el auto con SweetAlert2 -->
                            <form action="{{ route('cars.destroy', $car) }}" method="POST" id="deleteForm{{ $car->id }}" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-red-500 hover:text-red-700" onclick="deleteCar({{ $car->id }})">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal de edición -->
                <div class="modal fade" id="editCarModal{{ $car->id }}" tabindex="-1" aria-labelledby="editCarModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editCarModalLabel">Editar Auto</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('cars.update', $car) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="model" class="form-label">Modelo</label>
                                        <input type="text" name="model" id="model" class="form-control" value="{{ $car->model }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="license_plate" class="form-label">Placa</label>
                                        <input type="text" name="license_plate" id="license_plate" class="form-control" value="{{ $car->license_plate }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Imagen</label>
                                        <input type="file" name="image" id="image" class="form-control">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $cars->links() }}
        </div>
    @endif
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Función para eliminar auto con SweetAlert2
    function deleteCar(carId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + carId).submit();
            }
        });
    }
</script>

@endsection
