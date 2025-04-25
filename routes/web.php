<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;

// 🔹 Rutas personalizadas de CATEGORÍAS
Route::get('/categorias/deshabilitadas', [CategoriaController::class, 'deshabilitadas'])->name('categorias.deshabilitadas');
Route::put('/categorias/{id}/habilitar', [CategoriaController::class, 'habilitar'])->name('categorias.habilitar');

// 🔹 Resource de CATEGORÍAS
Route::resource('categorias', CategoriaController::class);

// 🔹 Rutas personalizadas de USUARIOS
Route::get('/usuarios/deshabilitadas', [UsuarioController::class, 'deshabilitadas'])->name('usuarios.deshabilitadas');
Route::patch('/usuarios/{id}/deshabilitar', [UsuarioController::class, 'destroy'])->name('usuarios.deshabilitar');
Route::patch('/usuarios/{id}/habilitar', [UsuarioController::class, 'habilitar'])->name('usuarios.habilitar');

// 🔹 Resource de USUARIOS
Route::resource('usuarios', UsuarioController::class);

// 🔹 Rutas de autenticación
Route::get('/login', [AuthController::class, 'mostrarFormulario'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔹 Rutas de dashboards
Route::view('/administrador/dashboard', 'Administrador.dashboard')->name('Administrador.dashboard');
Route::view('/cocina/dashboard', 'Cocina.dashboard')->name('Cocina.dashboard');
Route::view('/caja/dashboard', 'Caja.dashboard')->name('Caja.dashboard');

// 🔹 Ruta de inicio
Route::get('/', function () {
    return view('welcome');
});