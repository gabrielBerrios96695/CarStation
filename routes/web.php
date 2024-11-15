<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PasswordChangeController;

use App\Http\Controllers\PackagesController;
use App\Http\Controllers\PurchaseController;

// Agrupando las rutas bajo autenticación
Route::middleware(['auth'])->group(function () {
    // Ruta para mostrar la lista de compras
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');

    // Ruta para mostrar el formulario de compra de un paquete específico
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    // routes/web.php
Route::post('/purchases/{package}', [PurchaseController::class, 'store'])->name('purchases.store');


});

// Rutas para la gestión de paquetes
Route::get('/packages', [PackagesController::class, 'index'])->name('packages.index');  // Ver lista de paquetes
Route::get('/packages/create', [PackagesController::class, 'create'])->name('packages.create');  // Formulario para crear paquete
Route::post('/packages', [PackagesController::class, 'store'])->name('packages.store');  // Guardar nuevo paquete
Route::get('/packages/{package}/edit', [PackagesController::class, 'edit'])->name('packages.edit');  // Formulario para editar paquete
Route::put('/packages/{package}', [PackagesController::class, 'update'])->name('packages.update');  // Actualizar paquete existente
Route::delete('/packages/{package}', [PackagesController::class, 'destroy'])->name('packages.destroy');  // Eliminar paquete

// Ruta para exportar paquetes en formato Excel
Route::get('/packages/export', [PackageController::class, 'export'])->name('packages.export');


Route::middleware(['auth'])->group(function () {
    Route::get('/password-change', [PasswordChangeController::class, 'showChangeForm'])->name('password.change');
    Route::post('/password-change', [PasswordChangeController::class, 'updatePassword']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

    use App\Http\Controllers\ReservaController;

// Otras rutas

Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');

Route::get('/parkings', [ParkingController::class, 'index'])->name('parkings.index');
Route::get('/parkings/create', [ParkingController::class, 'create'])->name('parkings.create');
Route::post('/parkings', [ParkingController::class, 'store'])->name('parkings.store');
Route::get('/parkings/{parking}/edit', [ParkingController::class, 'edit'])->name('parkings.edit');
Route::put('/parkings/{parking}', [ParkingController::class, 'update'])->name('parkings.update');
Route::delete('/parkings/{parking}', [ParkingController::class, 'destroy'])->name('parkings.destroy');
Route::put('/parkings/{parking}/toggle-status', [ParkingController::class, 'toggleStatus'])->name('parkings.toggleStatus');
Route::get('/parkings/export', [ParkingController::class, 'exportToExcel'])->name('parkings.export');
Route::get('/parkings/view/{id}', [ParkingController::class, 'view'])->name('parkings.view');
Route::get('/parkings/maps', [ParkingController::class, 'maps'])->name('parkings.maps');
Route::post('/parkings/reserve', [ParkingController::class, 'reserve'])->name('parkings.reserve');
Route::get('parkings/{id}', [ParkingController::class, 'view'])->name('parkings.view');
Route::get('reservas/hours-available', [ParkingController::class, 'availableHours'])->name('reservas.availableHours');
Route::post('reservas', [ParkingController::class, 'storeReservation'])->name('reservas.store');
Route::post('/reservar/{plaza_id}', [ReservationController::class, 'store'])->name('reservar');

Route::post('/reservations/store', [ParkingController::class, 'storeReservation'])->name('reservations.store');

use App\Http\Controllers\ReservationController;

Route::post('/reservar', [ReservationController::class, 'store'])->name('reservar');
Route::get('/reservas', [ReservationController::class, 'index'])->name('reservas.index');
Route::delete('/reservas/{id}', [ReservationController::class, 'destroy'])->name('reservas.destroy');


    
    //Usuarios
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::get('/users/{id}/destroy', [UserController::class, 'destroy'])->name('users.destroy');
Route::put('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
Route::get('/users/export', [UserController::class, 'exportToExcel'])->name('users.export');
Route::get('/users/create-admin', [UserController::class, 'createAdmin'])->name('users.createAdmin');
Route::post('/users/store-admin', [UserController::class, 'storeAdmin'])->name('users.storeAdmin');

use App\Http\Controllers\ReportController;

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/client-reservations', [ReportController::class, 'clientReservations'])->name('reports.clientReservations');
Route::get('/reports/export', [ReportController::class, 'exportPdf'])->name('reports.export');
// Ruta para mostrar el reporte de usuarios frecuentes con filtro por fechas
Route::get('/reports/frequent-users', [ReportController::class, 'clientReservations2'])->name('reports.clientReservations2');

// Ruta para exportar el reporte de usuarios frecuentes a PDF (puedes modificar el método de exportación según lo necesites)
Route::get('/reports/frequent-users/export', [ReportController::class, 'exportFrequentUsers'])->name('reports.exportFrequentUsers');
require __DIR__.'/auth.php';
