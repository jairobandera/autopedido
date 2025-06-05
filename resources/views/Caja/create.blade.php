{{-- resources/views/Caja/create.blade.php --}}
@extends('layouts.app-caja')

@section('title', 'Nuevo Pedido')

<style>
    /* Desenfocar sutilmente el contenido cuando aparece el backdrop */
    .modal-backdrop.show {
        backdrop-filter: blur(3px);
        opacity: 0.6 !important;
    }
</style>

@section('content')
    {{-- ---- BOTONES PRINCIPALES ---- --}}
    <div class="d-flex justify-content-between mb-3">
        {{-- 1) Botón para abrir listado de productos --}}
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProductos">
            <i class="bi bi-search"></i> Buscar Productos
        </button>

        {{-- --- Botón para asociar cliente --}}
        <button id="btn-asociar-cliente" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalClientes">
            <i class="bi bi-person-plus"></i> Asociar Cliente
        </button>
    </div>

    {{-- ---- MODALES ---- --}}

    {{-- 2. Modal: Listado de Productos --}}
    <div class="modal fade" id="modalProductos" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Productos</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Buscador --}}
                    <div class="input-group mb-3">
                        <input id="search-productos" type="text" class="form-control" placeholder="Buscar...">
                        <button id="btn-search" class="btn btn-outline-secondary">Buscar</button>
                    </div>
                    {{-- Tabla paginada --}}
                    <div id="listado-productos">
                        {{-- Se llenará vía AJAX --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Modal: Asignar Cliente --}}
    <div class="modal fade" id="modalClientes" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buscar Cliente por Cédula</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Input para cédula --}}
                    <div class="input-group mb-3">
                        <input id="input-cedula" type="text" class="form-control" placeholder="Ingresa cédula...">
                        <button id="btn-buscar-cliente" class="btn btn-primary">Buscar</button>
                    </div>
                    {{-- Tabla resultados --}}
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabla-clientes">
                            <thead>
                                <tr>
                                    <th>Cédula</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Teléfono</th>
                                    <th>Puntos</th>
                                    <th>Activo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Se llenará con AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- Botón para cerrar sin seleccionar --}}
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Modal: Detalle de un Producto --}}
    <div class="modal fade" id="modalDetalle" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="d-titulo"></h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img id="d-imagen" class="img-fluid mb-3">
                    <p>Precio: $<span id="d-precio"></span></p>
                    <div id="d-ingredientes" class="mb-3">
                        {{-- Checkboxes generados por JS --}}
                    </div>
                    <div class="mb-3">
                        <label>Cantidad</label>
                        <input id="d-cantidad" type="number" min="1" value="1" class="form-control">
                    </div>
                    <button id="d-agregar" type="button" class="btn btn-success w-100" data-id="" data-precio="">
                        Agregar al Carrito
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- 5. Tabla del Carrito --}}
    <h5>Carrito</h5>
    <div class="table-responsive mb-3">
        {{-- Método Pago --}}
        <div class="d-flex justify-content-end align-items-center mb-3">
            <label for="metodo-pago-global" class="me-2 mb-0">Método de Pago:</label>
            <select id="metodo-pago-global" class="form-select w-auto">
                <option value="Efectivo" selected>Efectivo</option>
                <option value="Tarjeta">Tarjeta</option>
            </select>
        </div>
        <table class="table table-sm" id="tabla-carrito">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Ingredientes Quitados</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {{-- Se llenará con JS --}}
            </tbody>
        </table>
    </div>

    {{-- Mostrar cliente seleccionado o “Sin cliente” --}}
    <div id="cliente-seleccionado" class="mb-3">
        Cliente: <span id="nombre-cliente">Sin cliente</span>
    </div>

    {{-- 6. Botón Finalizar Pedido --}}
    <button id="btn-finalizar" class="btn btn-success" disabled>Entregar Pedido</button>

    {{-- Modal Pedido Creado --}}
    <div class="modal fade" id="modalPedidoCreado" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        data-pedido-id="">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pedido Creado</h5>
                    {{-- Ocultamos la “X” de cierre --}}
                    <button type="button" class="btn-close d-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Código de pedido: <strong id="modal-codigo-nuevo"></strong></p>
                </div>
                <div class="modal-footer">
                    <button id="btn-imprimir-comprobante" class="btn btn-secondary">
                        Imprimir Comprobante
                    </button>
                    <button id="btn-ver-pedidos" class="btn btn-success">
                        Ver Pedidos
                    </button>
                    <button id="btn-otro-pedido" class="btn btn-primary">
                        Tomar otro pedido
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ——————————————— --}}
    {{-- VARIABLES PARA EL JS (data-attributes) --}}
    {{-- ——————————————— --}}

    {{-- Base URL para Productos (usada en caja/create.js) --}}
    <div id="base-prod-url" data-url="{{ url('/caja/productos') }}" class="d-none"></div>

    {{-- Ruta para guardar pedido (usada en caja/create.js) --}}
    <div id="store-pedido-url" data-url="{{ route('caja.pedidos.store') }}" class="d-none"></div>

    {{-- Ruta para redirigir a Dashboard (usada en caja/create.js) --}}
    <div id="dashboard-pedidos-url" data-url="{{ route('Caja.dashboard') }}" class="d-none"></div>
@endsection

@section('scripts')
    {{-- Incluir el JS externo (asegúrate de que el path corresponda) --}}
    <script src="{{ asset('js/caja/create.js') }}"></script>
@endsection