@extends('layouts.app-caja')

@section('title', 'Editar Pedido #' . $pedido->id)

@section('content')
    <div class="mb-3">
        <a href="{{ route('Caja.dashboard') }}" class="btn btn-secondary">&larr; Volver</a>
    </div>

    <h2>Editar Pedido: {{ $pedido->codigo }}</h2>

    {{-- ——— Mostrar/Asociar Cliente ——— --}}
    <div class="mb-4">
        @if ($pedido->cliente)
            <p><strong>Cliente asociado:</strong>
                <span id="info-cliente">
                    {{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}
                    (Cédula: {{ $pedido->cliente->cedula }})
                </span>
                <button id="btn-cambiar-cliente" class="btn btn-sm btn-warning ms-2">
                    <i class="bi bi-person-check"></i> Cambiar Cliente
                </button>
            </p>
        @else
            <p><strong>Cliente asociado:</strong> <span id="info-cliente">— Ninguno —</span>
                <button id="btn-asociar-cliente" class="btn btn-sm btn-success ms-2">
                    <i class="bi bi-person-plus"></i> Asociar Cliente
                </button>
            </p>
        @endif

        {{-- Campo oculto para enviar cliente_id en el payload --}}
        <input type="hidden" id="cliente-id" value="{{ $pedido->cliente_id }}">
    </div>
    {{-- ———————————————————————————————— --}}

    {{-- 1. Botón para abrir modal de selección --}}
    <button id="btn-abrir-prod" class="btn btn-primary mb-3">
        <i class="bi bi-search"></i> Buscar Productos
    </button>

    {{-- 2. Carrito --}}
    <div class="row">
        <div class="col-8">
            <table class="table table-sm" id="tabla-carrito">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Quitados</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody><!-- llenado por JS --></tbody>
            </table>
            {{-- Aquí mostramos el Total --}}
            <p class="fw-bold text-end">Total: $<span id="total-edit">0.00</span></p>
        </div>
        <div class="col-4">
            <div class="mb-3">
                <label>Método de Pago</label>
                <select id="metodo-pago" class="form-select">
                    <option value="Efectivo" {{ $pedido->metodo_pago === 'Efectivo' ? 'selected' : '' }}>
                        Efectivo
                    </option>
                    <option value="Tarjeta" {{ $pedido->metodo_pago === 'Tarjeta' ? 'selected' : '' }}>
                        Tarjeta
                    </option>
                </select>
            </div>
            <button id="btn-guardar" class="btn btn-success w-100" disabled>
                Guardar Cambios
            </button>
        </div>
    </div>

    {{-- 3. Modal: Selección de Productos --}}
    <div class="modal fade" id="modalSeleccionProducto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Seleccionar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Buscador --}}
                    <div class="input-group mb-3">
                        <input id="search-productos" type="text" class="form-control" placeholder="Buscar...">
                        <button id="btn-search" class="btn btn-outline-secondary">Buscar</button>
                    </div>
                    {{-- Listado --}}
                    <div id="listado-productos"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Modal: Detalle de Producto --}}
    <div class="modal fade" id="modalDetalleProducto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="d-titulo"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img id="d-imagen" class="img-fluid mb-3">
                    <p>Precio: $<span id="d-precio"></span></p>
                    <div id="d-ingredientes" class="mb-3"></div>
                    <div class="mb-3">
                        <label>Cantidad</label>
                        <input id="d-cantidad" type="number" min="1" value="1" class="form-control">
                    </div>
                    <button id="d-agregar" type="button" class="btn btn-success w-100">Agregar al Carrito</button>
                </div>
            </div>
        </div>
    </div>

    {{-- —— Modal: Buscar/Seleccionar Cliente —— --}}
    <div class="modal fade" id="modalClientes" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title">Buscar Cliente por Cédula</h5>
                    <div class="d-flex align-items-center">
                        <button id="btn-registrar-cliente-edit" type="button" class="btn btn-success btn-sm me-2">
                            <i class="bi bi-person-plus-fill"></i> Registrar Cliente
                        </button>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-body">
                    {{-- Input para cédula --}}
                    <div class="input-group mb-3">
                        <input id="input-cedula-edit" type="text" class="form-control" placeholder="Ingresa cédula...">
                        <button id="btn-buscar-cliente-edit" class="btn btn-primary">Buscar</button>
                    </div>
                    {{-- Tabla resultados --}}
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabla-clientes-edit">
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
                                {{-- se llena vía AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- Cerrar sin seleccionar --}}
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- ———————————————————————————————— --}}

    {{-- Modal: Registrar Cliente --}}
    <div class="modal fade" id="modalRegistrarCliente" tabindex="-1" aria-labelledby="modalRegistrarClienteLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-registrar-cliente-edit" action="{{ route('caja.clientes.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="rc-cedula-edit" class="form-label">Cédula <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="rc-cedula-edit" name="cedula" required>
                            <div class="invalid-feedback" id="error-cedula"></div>
                        </div>
                        <div class="mb-3">
                            <label for="rc-nombre-edit" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="rc-nombre-edit" name="nombre" required>
                            <div class="invalid-feedback" id="error-nombre"></div>
                        </div>
                        <div class="mb-3">
                            <label for="rc-apellido-edit" class="form-label">Apellido <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="rc-apellido-edit" name="apellido" required>
                            <div class="invalid-feedback" id="error-apellido"></div>
                        </div>
                        <div class="mb-3">
                            <label for="rc-telefono-edit" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="rc-telefono-edit" name="telefono">
                            <div class="invalid-feedback" id="error-telefono"></div>
                        </div>
                        {{-- Agrega aquí cualquier otro campo que necesites --}}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-guardar-cliente-edit" class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar Cliente
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const pedidoId = {{ $pedido->id }};
            const updateUrl = `{{ url('/caja/pedidos') }}/${pedidoId}`;
            const baseProdUrl = "{{ url('/caja/productos') }}";

            // —— MANEJO DE CLIENTE —— //
            let clienteSeleccionado = {{ $pedido->cliente_id ?? 'null' }};

            // Actualizar visualización de “info-cliente”
            function renderClienteInfo(nombre = '', apellido = '', cedula = '') {
                const span = document.getElementById('info-cliente');
                if (nombre && apellido && cedula) {
                    span.textContent = `${nombre} ${apellido} (Cédula: ${cedula})`;
                } else {
                    span.textContent = '— Ninguno —';
                }
            }

            // 1) Si hay cliente inicial, sus datos ya están en Blade; de lo contrario se queda “— Ninguno —”

            // 2) Al clicar “Asociar Cliente” o “Cambiar Cliente” abrimos el modal
            document.getElementById('btn-asociar-cliente')?.addEventListener('click', () => {
                new bootstrap.Modal(document.getElementById('modalClientes')).show();
            });
            document.getElementById('btn-cambiar-cliente')?.addEventListener('click', () => {
                new bootstrap.Modal(document.getElementById('modalClientes')).show();
            });

            // 3) Buscar cliente por cédula (modalClients, edición)
            document.getElementById('btn-buscar-cliente-edit').addEventListener('click', () => {
                const cedula = document.getElementById('input-cedula-edit').value.trim();
                if (!cedula) {
                    Swal.fire({ icon: 'warning', text: 'Ingresa una cédula para buscar.' });
                    return;
                }
                const tbody = document.querySelector('#tabla-clientes-edit tbody');
                tbody.innerHTML = '<tr><td colspan="7" class="text-center">Buscando...</td></tr>';

                fetch(`/caja/clientes/search?cedula=${encodeURIComponent(cedula)}`, {
                    headers: { Accept: 'application/json' }
                })
                    .then(res => res.json())
                    .then(data => {
                        tbody.innerHTML = '';
                        const activos = data.filter(c => c.activo);
                        const inactivos = data.filter(c => !c.activo);

                        if (inactivos.length > 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Cliente inactivo',
                                text: 'El cliente existe pero está inactivo.',
                            });
                        }
                        if (activos.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">No se encontraron clientes activos con esa cedula.</td></tr>';
                            return;
                        }
                        activos.forEach(cliente => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                                        <td>${cliente.cedula}</td>
                                                        <td>${cliente.nombre}</td>
                                                        <td>${cliente.apellido}</td>
                                                        <td>${cliente.telefono}</td>
                                                        <td>${cliente.puntos}</td>
                                                        <td>${cliente.activo ? 'Sí' : 'No'}</td>
                                                        <td>
                                                          <button class="btn btn-sm btn-primary btn-seleccionar-cliente-edit"
                                                                  data-id="${cliente.id}"
                                                                  data-nombre="${cliente.nombre}"
                                                                  data-apellido="${cliente.apellido}"
                                                                  data-cedula="${cliente.cedula}">
                                                            Seleccionar
                                                          </button>
                                                        </td>`;
                            tbody.appendChild(tr);
                        });
                    })
                    .catch(() => {
                        tbody.innerHTML = '<tr><td colspan="7" class="text-danger text-center">Error al buscar.</td></tr>';
                    });
            });

            // 4) Seleccionar cliente de la lista
            document.querySelector('#tabla-clientes-edit tbody').addEventListener('click', e => {
                if (!e.target.matches('.btn-seleccionar-cliente-edit')) return;
                const btn = e.target;
                const id = +btn.dataset.id;
                const nombre = btn.dataset.nombre;
                const apellido = btn.dataset.apellido;
                const cedula = btn.dataset.cedula;

                clienteSeleccionado = id;
                renderClienteInfo(nombre, apellido, cedula);
                document.getElementById('cliente-id').value = id;

                // Cerrar modal
                bootstrap.Modal.getInstance(document.getElementById('modalClientes')).hide();
            });

            // Botón que abre el modal de Registrar Cliente
            const btnRegistrarClienteEdit = document.getElementById('btn-registrar-cliente-edit');
            const modalRegistrarClienteEl = document.getElementById('modalRegistrarCliente');
            const modalRegistrarCliente = new bootstrap.Modal(modalRegistrarClienteEl, {
                backdrop: 'static',
                keyboard: false
            });

            // Inputs y botón del modal Registrar Cliente
            const formRegistrarClienteEdit = document.getElementById('form-registrar-cliente-edit');
            const btnGuardarClienteEdit = document.getElementById('btn-guardar-cliente-edit');

            // Al hacer clic en “Registrar Cliente”, abrimos el modal
            btnRegistrarClienteEdit.addEventListener('click', () => {
                // Limpiamos cualquier validación anterior
                ['rc-cedula-edit', 'rc-nombre-edit', 'rc-apellido-edit', 'rc-telefono-edit'].forEach(id => {
                    document.getElementById(id).classList.remove('is-invalid');
                    document.getElementById('error-' + id.split('-')[1]).textContent = '';
                });
                formRegistrarClienteEdit.reset();
                modalRegistrarCliente.show();
            });

            // Al presionar “Guardar Cliente” dentro de ese modal:
            btnGuardarClienteEdit.addEventListener('click', async () => {
                // Limpiar validaciones previas
                ['rc-cedula-edit', 'rc-nombre-edit', 'rc-apellido-edit', 'rc-telefono-edit'].forEach(id => {
                    document.getElementById(id).classList.remove('is-invalid');
                    document.getElementById('error-' + id.split('-')[1]).textContent = '';
                });

                // Tomar valores
                const cedula = document.getElementById('rc-cedula-edit').value.trim();
                const nombre = document.getElementById('rc-nombre-edit').value.trim();
                const apellido = document.getElementById('rc-apellido-edit').value.trim();
                const telefono = document.getElementById('rc-telefono-edit').value.trim();

                const payload = { cedula, nombre, apellido, telefono };

                try {
                    // URL desde el action del form
                    const url = formRegistrarClienteEdit.getAttribute('action');
                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    if (response.status === 422) {
                        // Errores de validación
                        const errors = await response.json();
                        for (const campo in errors.errors) {
                            const inputId = 'rc-' + campo + '-edit'; // ej: "rc-nombre-edit"
                            const feedbackId = 'error-' + campo;
                            const inputElem = document.getElementById(inputId);
                            const feedbackEl = document.getElementById(feedbackId);
                            if (inputElem) inputElem.classList.add('is-invalid');
                            if (feedbackEl) feedbackEl.textContent = errors.errors[campo][0];
                        }
                        return;
                    }

                    const data = await response.json();
                    if (data.success) {
                        // 1) Cerrar el modal de “Registrar Cliente”
                        modalRegistrarCliente.hide();

                        // 2) Mostrar un toast o notificación
                        Swal.fire({
                            icon: 'success',
                            title: 'Cliente registrado',
                            text: `Se creó ${data.cliente.nombre} ${data.cliente.apellido}.`,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // 3) Asociar automáticamente ese nuevo cliente al pedido:
                        clienteSeleccionado = data.cliente.id;
                        renderClienteInfo(data.cliente.nombre, data.cliente.apellido, data.cliente.cedula);
                        document.getElementById('cliente-id').value = data.cliente.id;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'No se pudo crear el cliente.',
                        });
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de red',
                        text: 'No se pudo comunicar con el servidor.',
                    });
                }
            });
            // —— FIN MANEJO DE REGISTRAR CLIENTE —— //

            // Arrancamos con los detalles (quitados ya es [{id,nombre},…])
            let carrito = @json($detalles);
            let pagina = 1, termino = '';

            // 1) Renderizar carrito
            function renderCarrito() {
                const tbody = document.querySelector('#tabla-carrito tbody');
                tbody.innerHTML = '';

                carrito.forEach((item, idx) => {
                    const quitadosTexto = item.quitados.length
                        ? item.quitados.map(q => q.nombre).join(', ')
                        : '<em>ninguno</em>';

                    tbody.insertAdjacentHTML('beforeend', `
                                                            <tr>
                                                              <td>${item.nombre}</td>
                                                              <td>${quitadosTexto}</td>
                                                              <td>
                                                                <input type="number"
                                                                       class="form-control form-control-sm cantidad-input"
                                                                       data-index="${idx}"
                                                                       min="1"
                                                                       value="${item.cantidad}">
                                                              </td>
                                                              <td>$${item.subtotal.toFixed(2)}</td>
                                                              <td>
                                                                <button class="btn btn-sm btn-danger btn-eliminar"
                                                                        data-index="${idx}">&times;</button>
                                                              </td>
                                                            </tr>
                                                          `);
                });

                // total y habilitar botón
                const total = carrito.reduce((s, it) => s + it.subtotal, 0);
                document.getElementById('total-edit').textContent = total.toFixed(2);
                document.getElementById('btn-guardar').disabled = carrito.length === 0;
            }

            // 2) cambiar cantidad
            document.querySelector('#tabla-carrito tbody').addEventListener('input', e => {
                if (!e.target.matches('.cantidad-input')) return;
                const idx = +e.target.dataset.index;
                const val = Math.max(1, parseInt(e.target.value, 10) || 1);
                carrito[idx].cantidad = val;
                carrito[idx].subtotal = carrito[idx].precio * val;
                // actualizar fila y total
                const row = e.target.closest('tr');
                row.querySelector('td:nth-child(4)').textContent = '$' + carrito[idx].subtotal.toFixed(2);
                const total = carrito.reduce((s, it) => s + it.subtotal, 0);
                document.getElementById('total-edit').textContent = total.toFixed(2);
            });

            // 3) eliminar item
            document.querySelector('#tabla-carrito tbody').addEventListener('click', e => {
                if (!e.target.matches('.btn-eliminar')) return;
                carrito.splice(+e.target.dataset.index, 1);
                renderCarrito();
            });

            // 4) inicial render
            renderCarrito();

            // 5) loadProductos / búsqueda / selección
            function loadProductos() {
                fetch(`${baseProdUrl}?page=${pagina}&q=${encodeURIComponent(termino)}`, {
                    headers: { Accept: 'application/json' }
                })
                    .then(r => r.json())
                    .then(data => {
                        let html = '<table class="table"><thead><tr><th>Imagen</th><th>Nombre</th><th>Precio</th><th></th></tr></thead><tbody>';
                        data.data.forEach(p => {
                            html += `
                                                                <tr>
                                                                  <td><img src="${p.imagen}" style="height:40px"></td>
                                                                  <td>${p.nombre}</td>
                                                                  <td>$${parseFloat(p.precio).toFixed(2)}</td>
                                                                  <td>
                                                                    <button class="btn btn-sm btn-primary btn-seleccionar" data-id="${p.id}">
                                                                      Seleccionar
                                                                    </button>
                                                                  </td>
                                                                </tr>`;
                        });
                        html += '</tbody></table>';
                        html += `<div class="d-flex justify-content-center my-2"><nav>${data.links}</nav></div>`;
                        document.getElementById('listado-productos').innerHTML = html;
                    });
            }

            document.getElementById('btn-abrir-prod').onclick = () => {
                pagina = 1; termino = '';
                loadProductos();
                new bootstrap.Modal(document.getElementById('modalSeleccionProducto')).show();
            };

            document.getElementById('listado-productos').addEventListener('click', e => {
                const link = e.target.closest('a.page-link');
                if (link) {
                    e.preventDefault();
                    pagina = new URL(link.href).searchParams.get('page') || 1;
                    loadProductos();
                    return;
                }
                if (e.target.matches('.btn-seleccionar')) {
                    fetch(`${baseProdUrl}/${e.target.dataset.id}`, { headers: { Accept: 'application/json' } })
                        .then(r => r.json())
                        .then(p => {
                            // rellenar modal detalle
                            document.getElementById('d-titulo').textContent = p.nombre;
                            document.getElementById('d-imagen').src = p.imagen;
                            document.getElementById('d-precio').textContent = parseFloat(p.precio).toFixed(2);
                            document.getElementById('d-cantidad').value = 1;
                            const cont = document.getElementById('d-ingredientes');
                            cont.innerHTML = '<label>Quitar ingredientes:</label>';
                            p.ingredientes.forEach(ing => {
                                cont.innerHTML += `
                                                                  <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                           value="${ing.id}" id="ing-${ing.id}"
                                                                           ${ing.es_obligatorio ? 'disabled checked' : ''}>
                                                                    <label class="form-check-label" for="ing-${ing.id}">
                                                                      ${ing.nombre}${ing.es_obligatorio ? ' (obligatorio)' : ''}
                                                                    </label>
                                                                  </div>`;
                            });
                            // guardar id/precio
                            const btnAdd = document.getElementById('d-agregar');
                            btnAdd.dataset.id = p.id;
                            btnAdd.dataset.precio = p.precio;
                            new bootstrap.Modal(document.getElementById('modalDetalleProducto')).show();
                        });
                }
            });

            // 6) agregar nuevo item
            document.getElementById('d-agregar').onclick = () => {
                const btn = document.getElementById('d-agregar');
                const id = btn.dataset.id;
                const nombre = document.getElementById('d-titulo').textContent;
                const precio = parseFloat(btn.dataset.precio);
                const cantidad = parseInt(document.getElementById('d-cantidad').value, 10);
                const quitados = Array.from(
                    document.querySelectorAll('#d-ingredientes .form-check-input:checked')
                ).filter(ch => !ch.disabled)
                    .map(ch => {
                        const id = parseInt(ch.value, 10);
                        let nombre = document.querySelector(`label[for="${ch.id}"]`).textContent;
                        nombre = nombre.replace(/\s*\(obligatorio\)$/, '').trim();
                        return { id, nombre };
                    });

                carrito.push({ id, nombre, precio, cantidad, quitados, subtotal: precio * cantidad });
                renderCarrito();
                bootstrap.Modal.getInstance(document.getElementById('modalDetalleProducto')).hide();
            };

            // 7) guardar cambios
            document.getElementById('btn-guardar').onclick = () => {
                const clienteIdValue = parseInt(document.getElementById('cliente-id').value) || null;
                const payload = {
                    cliente_id: clienteIdValue,
                    items: carrito.map(item => ({
                        id: item.id,
                        cantidad: item.cantidad,
                        quitados: item.quitados.map(q => q.id)
                    })),
                    metodo_pago: document.getElementById('metodo-pago').value
                };

                // ------- Agrega estos console.log para inspeccionar ------
                console.log('DEBUG antes de enviar PATCH a:', updateUrl);
                console.log('DEBUG payload:', payload);
                // ----------------------------------------------------------

                fetch(updateUrl, {
                    method: 'PATCH',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                })
                    .then(r => {
                        console.log('DEBUG respuesta status:', r.status, 'ok?', r.ok);
                        return r.ok ? r.json() : r.json().then(err => Promise.reject(err));
                    })
                    .then(json => {
                        console.log('DEBUG respuesta JSON:', json);
                        return Swal.fire('Listo', 'Pedido actualizado', 'success')
                            .then(() => window.location.href = '{{ route("Caja.dashboard") }}');
                    })
                    .catch(error => {
                        console.error('DEBUG error en fetch PATCH:', error);
                        const msg = error.error || 'No se pudo actualizar';
                        Swal.fire('Error', msg, 'error');
                    });
            };
        }); //DOMContentLoaded
    </script>
@endsection