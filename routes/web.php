<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\AuthController;


// 🔹 Primero las rutas personalizadas
Route::get('/categorias/deshabilitadas', [CategoriaController::class, 'deshabilitadas'])->name('categorias.deshabilitadas');
Route::put('/categorias/{id}/habilitar', [CategoriaController::class, 'habilitar'])->name('categorias.habilitar');

// 🔹 Después el resource de CATEGORÍAS (solo una vez)
Route::resource('categorias', CategoriaController::class);

Route::get('/login', [AuthController::class, 'mostrarFormulario'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::view('/administrador/dashboard', 'Administrador.dashboard')->name('Administrador.dashboard');
Route::view('/cocina/dashboard', 'Cocina.dashboard')->name('Cocina.dashboard');
Route::view('/caja/dashboard', 'Caja.dashboard')->name('Caja.dashboard');



Route::get('/', function () {
    return view('welcome');
});

