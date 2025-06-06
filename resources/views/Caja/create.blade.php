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

        {{-- Contenedor de “Acciones para el cliente” a la derecha --}}
        <div class="d-flex align-items-center">
            <span class="me-2 fw-bold">Acciones para el cliente:</span>
            <div class="d-flex">
                {{-- Botón “Asociar Cliente” --}}
                <button id="btn-asociar-cliente" class="btn btn-info me-2" data-bs-toggle="modal"
                    data-bs-target="#modalClientes">
                    <i class="bi bi-person-plus"></i> Asociar Cliente
                </button>

                {{-- Botón “Registrar Cliente” --}}
                <button id="btn-registrar-cliente" class="btn btn-success me-2" data-bs-toggle="modal"
                    data-bs-target="#modalRegistrarCliente">
                    <i class="bi bi-person-plus-fill"></i> Registrar Cliente
                </button>

                {{-- Botón “Consultar Puntos” --}}
                <button id="btn-consultar-puntos" class="btn btn-warning" data-bs-toggle="modal"
                    data-bs-target="#modalConsultarPuntos">
                    <i class="bi bi-coin"></i> Consultar Puntos
                </button>
            </div>
        </div>
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

    {{-- 6. Modal: Registrar Cliente --}}
    <div class="modal fade" id="modalRegistrarCliente" tabindex="-1" aria-labelledby="modalRegistrarClienteLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- El formulario tendrá un ID para que lo capturemos desde JavaScript --}}
                    <form id="form-registrar-cliente" action="{{ route('caja.clientes.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="rc-cedula" class="form-label">Cédula <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="rc-cedula" name="cedula" required>
                            <div class="invalid-feedback" id="error-cedula"></div>
                        </div>
                        <div class="mb-3">
                            <label for="rc-nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="rc-nombre" name="nombre" required>
                            <div class="invalid-feedback" id="error-nombre"></div>
                        </div>
                        <div class="mb-3">
                            <label for="rc-apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="rc-apellido" name="apellido" required>
                            <div class="invalid-feedback" id="error-apellido"></div>
                        </div>
                        <div class="mb-3">
                            <label for="rc-telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="rc-telefono" name="telefono">
                            <div class="invalid-feedback" id="error-telefono"></div>
                        </div>
                        {{-- Aquí podés agregar otros campos que necesites --}}
                    </form>
                </div>
                <div class="modal-footer">
                    {{-- Botón para cerrar sin guardar --}}
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    {{-- Botón que disparará la petición AJAX --}}
                    <button type="button" id="btn-guardar-cliente" class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar Cliente
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- 7. Modal: Consultar Puntos --}}
    <div class="modal fade" id="modalConsultarPuntos" tabindex="-1" aria-labelledby="modalConsultarPuntosLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Consultar Puntos de Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Input para ingresar la cédula a consultar --}}
                    <form id="form-consultar-puntos">
                        <div class="mb-3">
                            <label for="cp-cedula" class="form-label">Cédula del Cliente <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cp-cedula" name="cedula" required>
                            <div class="invalid-feedback" id="error-cp-cedula"></div>
                        </div>
                        <button type="button" id="btn-buscar-puntos" class="btn btn-warning w-100 mb-3">
                            <i class="bi bi-search"></i> Buscar Puntos
                        </button>
                    </form>

                    {{-- Aquí mostraremos el resultado tras la petición AJAX --}}
                    <div id="resultado-puntos" class="d-none">
                        <p><strong>Cliente:</strong> <span id="rp-nombre"></span> <span id="rp-apellido"></span></p>
                        <p><strong>Cédula:</strong> <span id="rp-cedula"></span></p>
                        <p><strong>Teléfono:</strong> <span id="rp-telefono"></span></p>
                        <p><strong>Puntos actuales:</strong> <span id="rp-puntos"></span></p>

                        {{-- Botón para abrir el modal de edición --}}
                        <button type="button" id="btn-editar-cliente" class="btn btn-primary mt-2">
                            <i class="bi bi-pencil-square"></i> Editar Cliente
                        </button>
                    </div>

                    <div id="mensaje-puntos" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    {{-- Solo botón para cerrar --}}
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 8. Modal: Editar Cliente --}}
    <div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalEditarClienteLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- El formulario tendrá ID para capturarlo en JS --}}
                    <form id="form-editar-cliente">
                        @csrf
                        {{-- Usaremos PATCH, así que agregamos el método en JS, no necesitamos @method('PATCH') aquí --}}
                        <input type="hidden" id="ec-id" name="id">

                        <div class="mb-3">
                            <label for="ec-cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="ec-cedula" name="cedula" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="ec-nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ec-nombre" name="nombre" required>
                            <div class="invalid-feedback" id="error-ec-nombre"></div>
                        </div>
                        <div class="mb-3">
                            <label for="ec-apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ec-apellido" name="apellido" required>
                            <div class="invalid-feedback" id="error-ec-apellido"></div>
                        </div>
                        <div class="mb-3">
                            <label for="ec-telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="ec-telefono" name="telefono">
                            <div class="invalid-feedback" id="error-ec-telefono"></div>
                        </div>
                        {{-- Si quieres exponer otros campos editables, agrégalos aquí --}}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-guardar-edicion" class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
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

    {{-- Ruta para consultar puntos de cliente --}}
    <div id="puntos-cliente-url" data-url="{{ route('caja.clientes.puntosCedula') }}" class="d-none"></div>
    
    {{-- Ruta para actualizar cliente: genera "/caja/clientes/__ID__" --}}
    <div id="update-cliente-url" data-url="{{ route('caja.clientes.update', ['id' => '__ID__']) }}" class="d-none"></div>
@endsection

@section('scripts')
    {{-- Incluir el JS externo (asegúrate de que el path corresponda) --}}
    <script src="{{ asset('js/caja/create.js') }}"></script>
@endsection