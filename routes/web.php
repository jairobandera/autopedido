<?php

use App\Http\Controllers\MercadoPagoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// 🔹 Registrar los middlewares
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CajeroMiddleware;
use App\Http\Middleware\CocinaMiddleware;

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
    });

    // 🔹 Rutas protegidas para cajeros (verificar rol)
    Route::middleware('cajero')->group(function () {
        // Dashboard de Cajero – ahora con controller
        Route::get('/caja/dashboard', [PedidoController::class, 'index'])
            ->name('Caja.dashboard');

        //Ultimo Pedido
        // routes/web.php
        Route::get('/caja/pedidos/latest', [PedidoController::class, 'latest'])
            ->name('caja.pedidos.latest')
            ->middleware('auth', 'cajero');
        Route::get('/caja/pedidos/entregados', [PedidoController::class, 'entregados'])
            ->name('caja.pedidos.entregados');
        // Crear pedido
        Route::get('/caja/pedidos/create', [PedidoController::class, 'create'])
            ->name('caja.pedidos.create');
        // Mostrar formulario de edición
        Route::get('/caja/pedidos/{id}/edit', [PedidoController::class, 'edit'])
            ->name('caja.pedidos.edit');
        // Actualizar pedido
        Route::patch('/caja/pedidos/{id}', [PedidoController::class, 'update'])
            ->name('caja.pedidos.update');
        Route::get('/caja/productos/{id}', [PedidoController::class, 'detalleProducto'])
            ->name('caja.productos.show');
        Route::get('/caja/productos', [PedidoController::class, 'buscarProductos']) //búsqueda/paginación de productos:
            ->name('caja.productos.index');                                                  //detalle de un producto:
        Route::post('/caja/pedidos', [PedidoController::class, 'store'])
            ->name('caja.pedidos.store');
        Route::get('/caja/pedidos/{id}', [App\Http\Controllers\PedidoController::class, 'show'])
            ->name('caja.pedidos.show');
        // Marca un pedido como 'Entregado' o el estado que necesites
        Route::patch('/caja/pedidos/{id}/estado', [PedidoController::class, 'cambiarEstado'])
            ->name('caja.pedidos.estado');
        Route::patch('/caja/pagos/{pago}/estado', [PedidoController::class, 'cambiarPagoEstado'])
            ->name('caja.pagos.estado');
        Route::get('/caja/pedidos/{pedido}/comprobante', [PedidoController::class, 'comprobante'])
            ->name('caja.pedidos.comprobante');


        Route::resource('caja/pedidos', PedidoController::class, ['as' => 'caja']);

    });

    // 🔹 Rutas protegidas para cocina (verificar rol)
    Route::middleware('cocina')->group(function () {
        // 🔹 Dashboard de Cocina
        Route::view('/cocina/dashboard', 'Cocina.dashboard')->name('Cocina.dashboard');
    });

    // 🔹 Rutas de logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


});

