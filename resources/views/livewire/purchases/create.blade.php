@extends('layouts.app')

@section('breadcrumbs')
    Gestión de Compras
@endsection

@section('content')
<div class="container mt-5">
@if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row text-center">
        <h2 class="mb-4">Seleccionar un Paquete</h2>

        <div class="col-md-12 mb-4">
            <label for="userSelect" class="form-label">Seleccionar Cliente</label>
            <select class="form-select" id="userSelect">
                <option value="" selected disabled>Seleccione un cliente</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        @foreach ($packages as $package)
            <div class="col-md-4">
                <div class="card p-3 mb-3">
                    <h5>{{ $package->name }}</h5>
                    <img src="{{ asset('storage/qr_codes/' . $package->qr_code) }}" class="img-fluid mb-3" alt="QR Code">
                    <p>{{ $package->tokens }} Tokens</p>
                    <p>Monto:</p>
                    <span class="badge bg-primary p-2">${{ number_format($package->price, 2) }}</span>
                    <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#purchaseModal{{ $package->id }}">
                        Comprar
                    </button>
                </div>
            </div>

            <!-- Modal para confirmar la compra -->
            <div class="modal fade" id="purchaseModal{{ $package->id }}" tabindex="-1" aria-labelledby="purchaseModalLabel{{ $package->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="purchaseModalLabel{{ $package->id }}">Confirmar Compra</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <h5>{{ $package->name }}</h5>
                                <img src="{{ asset('storage/qr_codes/' . $package->qr_code) }}" class="img-fluid mb-3" alt="QR Code">
                                <p><strong>Tokens:</strong> {{ $package->tokens }}</p>
                                <p><strong>Monto:</strong> ${{ number_format($package->price, 2) }}</p>
                                <p><strong>Horas Compradas:</strong> {{ $package->hours }} horas</p> <!-- Mostrar horas compradas -->
                            </div>
                            
                            <div class="mb-3">
                                <label for="paymentMethod{{ $package->id }}" class="form-label">Método de Pago</label>
                                <select class="form-select" id="paymentMethod{{ $package->id }}">
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="QR">QR</option>
                                </select>
                            </div>

                            <form action="{{ route('purchases.store', $package->id) }}" method="POST" id="purchaseForm{{ $package->id }}">
                                @csrf
                                <input type="hidden" name="user_id" id="selectedUserId{{ $package->id }}" value="">
                                <input type="hidden" name="amount" value="{{ $package->price }}">
                                <input type="hidden" name="description" id="paymentDescription{{ $package->id }}" value="">
                                <input type="hidden" name="hours" id="purchasedHours{{ $package->id }}" value="{{ $package->hours }}"> <!-- Campo oculto para horas compradas -->
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="confirmPurchase({{ $package->id }})">Confirmar Compra</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row text-center mt-4">
        <p class="bg-primary text-white p-2">Los Tokens por 1 hora igual a 50 tokens</p>
    </div>
</div>

@push('scripts')
    <script>
        // Función para confirmar la compra y enviar el formulario
        function confirmPurchase(packageId) {
            var selectedUserId = document.getElementById('userSelect').value;
            var paymentMethod = document.getElementById('paymentMethod' + packageId).value; // Obtener el método de pago seleccionado

            if (!selectedUserId) {
                alert('Por favor, selecciona un cliente.');
                return;
            }

            document.getElementById('selectedUserId' + packageId).value = selectedUserId;
            document.getElementById('paymentDescription' + packageId).value = paymentMethod; // Método de pago

            document.getElementById('purchaseForm' + packageId).submit(); // Enviar el formulario
        }
    </script>
@endpush

@endsection
