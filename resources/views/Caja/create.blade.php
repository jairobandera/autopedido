@extends('layouts.app-caja')

@section('title', 'Nuevo Pedido')

<style>
    .modal-backdrop.show {
        backdrop-filter: blur(3px);
        opacity: 0.6 !important;
    }
    .table-responsive {
        min-height: 200px;
    }
    #tabla-carrito tbody tr {
        vertical-align: middle;
    }
</style>

@section('content')
    <!-- Botones Principales -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalProductos">
            <i class="bi bi-search me-1"></i> Buscar Productos
        </button>
        <div class="d-flex align-items-center">
            <span class="me-2 fw-bold text-muted">Acciones para el cliente:</span>
            <div class="d-flex gap-2">
                <button id="btn-asociar-cliente" class="btn btn-info rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#modalClientes">
                    <i class="bi bi-person-plus me-1"></i> Asociar Cliente
                </button>
                <button id="btn-registrar-cliente" class="btn btn-success rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#modalRegistrarCliente">
                    <i class="bi bi-person-plus-fill me-1"></i> Registrar Cliente
                </button>
                <button id="btn-consultar-puntos" class="btn btn-warning rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#modalConsultarPuntos">
                    <i class="bi bi-coin me-1"></i> Consultar Puntos
                </button>
            </div>
        </div>
    </div>

    <!-- Modales -->

    <!-- Modal: Listado de Productos -->
    <div class="modal fade" id="modalProductos" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Productos</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <input id="search-productos" type="text" class="form-control rounded-start" placeholder="Buscar...">
                        <button id="btn-search" class="btn btn-outline-secondary rounded-end">Buscar</button>
                    </div>
                    <div id="listado-productos" class="table-responsive">
                        <!-- Se llenará vía AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Asignar Cliente -->
    <div class="modal fade" id="modalClientes" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buscar Cliente por Cédula</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <input id="input-cedula" type="text" class="form-control rounded-start" placeholder="Ingresa cédula...">
                        <button id="btn-buscar-cliente" class="btn btn-primary rounded-end">Buscar</button>
                    </div>
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
                                <!-- Se llenará con AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Detalle de un Producto -->
    <div class="modal fade" id="modalDetalle" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="d-titulo"></h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img id="d-imagen" class="img-fluid mb-3 rounded" style="max-height: 200px; object-fit: cover;">
                    <p><strong>Precio:</strong> $<span id="d-precio"></span></p>
                    <div id="d-ingredientes" class="mb-3">
                        <!-- Checkboxes generados por JS -->
                    </div>
                    <div class="mb-3">
                        <label for="d-cantidad" class="form-label">Cantidad</label>
                        <input id="d-cantidad" type="number" min="1" value="1" class="form-control rounded">
                    </div>
                    <button id="d-agregar" type="button" class="btn btn-success w-100 rounded-pill" data-id="" data-precio="">
                        <i class="bi bi-cart-plus me-1"></i> Agregar al Carrito
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla del Carrito -->
    <h5 class="fw-bold">Carrito</h5>
    <div class="table-responsive mb-4">
        <div class="d-flex justify-content-end align-items-center mb-3">
            <label for="metodo-pago-global" class="me-2 mb-0 fw-bold text-muted">Método de Pago:</label>
            <select id="metodo-pago-global" class="form-select w-auto rounded">
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
                <!-- Se llenará con JS -->
            </tbody>
        </table>
    </div>

    <!-- Modal: Registrar Cliente -->
    <div class="modal fade" id="modalRegistrarCliente" tabindex="-1" aria-labelledby="modalRegistrarClienteLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-registrar-cliente" action="{{ route('caja.clientes.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="rc-cedula" class="form-label fw-bold">Cédula <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded" id="rc-cedula" name="cedula" required>
                            <div class="invalid-feedback" id="error-cedula"></div>
                        </div>
                        <div class="mb-3">
                            <label for="rc-nombre" class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded" id="rc-nombre" name="nombre" required>
                            <div class="invalid-feedback" id="error-nombre"></div>
                        </div>
                        <div class="mb-3">
                            <label for="rc-apellido" class="form-label fw-bold">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded" id="rc-apellido" name="apellido" required>
                            <div class="invalid-feedback" id="error-apellido"></div>
                        </div>
                        <div class="mb-3">
                            <label for="rc-telefono" class="form-label fw-bold">Teléfono</label>
                            <input type="text" class="form-control rounded" id="rc-telefono" name="telefono">
                            <div class="invalid-feedback" id="error-telefono"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-guardar-cliente" class="btn btn-success rounded-pill">
                        <i class="bi bi-save me-1"></i> Guardar Cliente
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Consultar Puntos -->
    <div class="modal fade" id="modalConsultarPuntos" tabindex="-1" aria-labelledby="modalConsultarPuntosLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Consultar Puntos de Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-consultar-puntos">
                        <div class="mb-3">
                            <label for="cp-cedula" class="form-label fw-bold">Cédula del Cliente <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded" id="cp-cedula" name="cedula" required>
                            <div class="invalid-feedback" id="error-cp-cedula"></div>
                        </div>
                        <button type="button" id="btn-buscar-puntos" class="btn btn-warning w-100 rounded-pill mb-3">
                            <i class="bi bi-search me-1"></i> Buscar Puntos
                        </button>
                    </form>
                    <div id="resultado-puntos" class="d-none">
                        <p><strong>Cliente:</strong> <span id="rp-nombre"></span> <span id="rp-apellido"></span></p>
                        <p><strong>Cédula:</strong> <span id="rp-cedula"></span></p>
                        <p><strong>Teléfono:</strong> <span id="rp-telefono"></span></p>
                        <p><strong>Puntos actuales:</strong> <span id="rp-puntos"></span></p>
                        <button type="button" id="btn-editar-cliente" class="btn btn-primary rounded-pill mt-2">
                            <i class="bi bi-pencil-square me-1"></i> Editar Cliente
                        </button>
                    </div>
                    <div id="mensaje-puntos" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Editar Cliente -->
    <div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalEditarClienteLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-editar-cliente">
                        @csrf
                        <input type="hidden" id="ec-id" name="id">
                        <div class="mb-3">
                            <label for="ec-cedula" class="form-label fw-bold">Cédula</label>
                            <input type="text" class="form-control rounded" id="ec-cedula" name="cedula" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="ec-nombre" class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded" id="ec-nombre" name="nombre" required>
                            <div class="invalid-feedback" id="error-ec-nombre"></div>
                        </div>
                        <div class="mb-3">
                            <label for="ec-apellido" class="form-label fw-bold">Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded" id="ec-apellido" name="apellido" required>
                            <div class="invalid-feedback" id="error-ec-apellido"></div>
                        </div>
                        <div class="mb-3">
                            <label for="ec-telefono" class="form-label fw-bold">Teléfono</label>
                            <input type="text" class="form-control rounded" id="ec-telefono" name="telefono">
                            <div class="invalid-feedback" id="error-ec-telefono"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-guardar-edicion" class="btn btn-success rounded-pill">
                        <i class="bi bi-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cliente Seleccionado -->
    <div id="cliente-seleccionado" class="mb-4 alert alert-light">
        Cliente: <span id="nombre-cliente">Sin cliente</span>
    </div>

    <!-- Botón Finalizar Pedido -->
    <button id="btn-finalizar" class="btn btn-success rounded-pill" disabled>
        <i class="bi bi-check-circle me-1"></i> Entregar Pedido
    </button>

    <!-- Modal Pedido Creado -->
    <div class="modal fade" id="modalPedidoCreado" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
         data-pedido-id="">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pedido Creado</h5>
                    <button type="button" class="btn-close d-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Código de pedido: <strong id="modal-codigo-nuevo"></strong></p>
                </div>
                <div class="modal-footer">
                    <button id="btn-imprimir-comprobante" class="btn btn-secondary rounded-pill">
                        <i class="bi bi-printer me-1"></i> Imprimir Comprobante
                    </button>
                    <button id="btn-ver-pedidos" class="btn btn-success rounded-pill">
                        <i class="bi bi-eye me-1"></i> Ver Pedidos
                    </button>
                    <button id="btn-otro-pedido" class="btn btn-primary rounded-pill">
                        <i class="bi bi-plus-lg me-1"></i> Tomar otro pedido
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Variables para JS -->
    <div id="base-prod-url" data-url="{{ url('/caja/productos') }}" class="d-none"></div>
    <div id="store-pedido-url" data-url="{{ route('caja.pedidos.store') }}" class="d-none"></div>
    <div id="dashboard-pedidos-url" data-url="{{ route('Caja.dashboard') }}" class="d-none"></div>
    <div id="puntos-cliente-url" data-url="{{ route('caja.clientes.puntosCedula') }}" class="d-none"></div>
    <div id="update-cliente-url" data-url="{{ route('caja.clientes.update', ['id' => '__ID__']) }}" class="d-none"></div>
@endsection

@section('scripts')
    <script src="{{ asset('js/caja/create.js') }}"></script>
@endsection