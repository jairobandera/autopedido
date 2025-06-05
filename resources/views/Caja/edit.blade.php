@extends('layouts.app-caja')

@section('title', 'Editar Pedido #' . $pedido->id)

@section('content')
    <div class="mb-3">
        <a href="{{ route('Caja.dashboard') }}" class="btn btn-secondary">&larr; Volver</a>
    </div>

    <h2>Editar Pedido: {{ $pedido->codigo }}</h2>

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
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const pedidoId = {{ $pedido->id }};
            const updateUrl = `{{ url('/caja/pedidos') }}/${pedidoId}`;
            const baseProdUrl = "{{ url('/caja/productos') }}";

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
                const payload = {
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