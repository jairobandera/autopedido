<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\PromocionController;
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
    // 🔹 Rutas protegidas para administradores (verificar rol)
    Route::middleware('admin')->group(function () {
        // 🔹 Dashboard de Administrador
        Route::view('/administrador/dashboard', 'Administrador.dashboard')->name('Administrador.dashboard');

        // 🔹 Rutas personalizadas de CATEGORÍAS
        Route::get('/administrador/categorias/deshabilitadas', [CategoriaController::class, 'deshabilitadas'])->name('categorias.deshabilitadas');
        Route::put('/administrador/categorias/{id}/habilitar', [CategoriaController::class, 'habilitar'])->name('categorias.habilitar');

        // 🔹 Resource de CATEGORÍAS
        Route::resource('/administrador/categorias', CategoriaController::class);

        // 🔹 Rutas personalizadas de USUARIOS
        Route::get('/administrador/usuarios/deshabilitadas', [UsuarioController::class, 'deshabilitadas'])->name('usuarios.deshabilitadas');
        Route::patch('/administrador/usuarios/{id}/deshabilitar', [UsuarioController::class, 'destroy'])->name('usuarios.deshabilitar');
        Route::patch('/administrador/usuarios/{id}/habilitar', [UsuarioController::class, 'habilitar'])->name('usuarios.habilitar');

        // 🔹 Resource de USUARIOS
        Route::resource('usuarios', UsuarioController::class);

        // 🔹 Rutas personalizadas de PRODUCTOS
        Route::get('/administrador/productos/deshabilitadas', [ProductoController::class, 'deshabilitadas'])->name('productos.deshabilitadas');
        Route::patch('/administrador/productos/{id}/deshabilitar', [ProductoController::class, 'destroy'])->name('productos.deshabilitar');
        Route::patch('/administrador/productos/{id}/habilitar', [ProductoController::class, 'habilitar'])->name('productos.habilitar');

        // 🔹 Resource de PRODUCTOS
        Route::resource('/administrador/productos', ProductoController::class)->except(['destroy']);

        // 🔹 Rutas personalizadas de INGREDIENTES
        Route::get('/administrador/ingredientes/deshabilitadas', [IngredienteController::class, 'deshabilitadas'])->name('ingredientes.deshabilitadas');
        Route::patch('/administrador/ingredientes/{id}/deshabilitar', [IngredienteController::class, 'destroy'])->name('ingredientes.deshabilitar');
        Route::patch('/administrador/ingredientes/{id}/habilitar', [IngredienteController::class, 'habilitar'])->name('ingredientes.habilitar');

        // 🔹 Resource de INGREDIENTES
        Route::resource('/administrador/ingredientes', IngredienteController::class)->except(['destroy']);

        // 🔹 Rutas personalizadas de PROMOCIONES
        Route::get('/administrador/promociones/deshabilitadas', [PromocionController::class, 'deshabilitadas'])
            ->name('promociones.deshabilitadas');
        Route::put('/administrador/promociones/{id}/habilitar', [PromocionController::class, 'habilitar'])
            ->name('promociones.habilitar');

        // Resource de PROMOCIONES
        Route::resource('/administrador/promociones', PromocionController::class);

    });

    // 🔹 Rutas protegidas para cajeros (verificar rol)
    Route::middleware('cajero')->group(function () {
        // 🔹 Dashboard de Cajero
        Route::view('/caja/dashboard', 'Caja.dashboard')->name('Caja.dashboard');
    });

    // 🔹 Rutas protegidas para cocina (verificar rol)
    Route::middleware('cocina')->group(function () {
        // 🔹 Dashboard de Cocina
        Route::view('/cocina/dashboard', 'Cocina.dashboard')->name('Cocina.dashboard');
    });

    // 🔹 Rutas de logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
