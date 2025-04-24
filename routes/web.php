<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PedidoController;


// 🔹 Primero las rutas personalizadas
Route::get('/categorias/deshabilitadas', [CategoriaController::class, 'deshabilitadas'])->name('categorias.deshabilitadas');
Route::put('/categorias/{id}/habilitar', [CategoriaController::class, 'habilitar'])->name('categorias.habilitar');

// 🔹 Después el resource de CATEGORÍAS (solo una vez)
Route::resource('categorias', CategoriaController::class);

Route::get('/', function () {
    return view('welcome');
});

