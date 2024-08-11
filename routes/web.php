<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PasswordChangeController;

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



Route::get('/parkings', [ParkingController::class, 'index'])->name('parkings.index');
Route::get('/parkings/create', [ParkingController::class, 'create'])->name('parkings.create');
Route::post('/parkings', [ParkingController::class, 'store'])->name('parkings.store');
Route::get('/parkings/{parking}/edit', [ParkingController::class, 'edit'])->name('parkings.edit');
Route::put('/parkings/{parking}', [ParkingController::class, 'update'])->name('parkings.update');
Route::delete('/parkings/{parking}', [ParkingController::class, 'destroy'])->name('parkings.destroy');
Route::put('/parkings/{parking}/toggle-status', [ParkingController::class, 'toggleStatus'])->name('parkings.toggleStatus');

    
    //Usuarios
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    
require __DIR__.'/auth.php';
