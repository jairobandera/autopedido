<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

require __DIR__ . '/auth.php';

// 🔹 Ruta raíz con redirección si el usuario está autenticado
Route::get('/', function () {
    // Si el usuario está autenticado, redirige a su dashboard según el rol
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->rol === 'Administrador') {
            return redirect()->route('Administrador.dashboard');
        } elseif ($user->rol === 'Cajero') {
            return redirect()->route('Caja.dashboard');
        } elseif ($user->rol === 'Cocina') {
            return redirect()->route('Cocina.dashboard');
        }
    }

    // Si no está autenticado, muestra la página principal
    return view('welcome');
});

// 🔹 Rutas para usuarios no autenticados (guest)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// 🔹 Rutas protegidas (solo accesibles por usuarios autenticados)
Route::middleware('auth')->group(function () {
    // 🔹 Rutas de CATEGORÍAS
    Route::resource('categorias', CategoriaController::class); // Usamos Route::resource para las categorías

    // 🔹 Rutas de logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // 🔹 Rutas de dashboard según rol
    Route::view('/administrador/dashboard', 'Administrador.dashboard')->name('Administrador.dashboard');
    Route::view('/cocina/dashboard', 'Cocina.dashboard')->name('Cocina.dashboard');
    Route::view('/caja/dashboard', 'Caja.dashboard')->name('Caja.dashboard');
});
