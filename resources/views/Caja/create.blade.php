@extends('layouts.app-caja')

@section('title', 'Nuevo Pedido')

@section('content')
    {{-- 1. Botón para abrir listado de productos --}}
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalProductos">
        <i class="bi bi-search"></i> Buscar Productos
    </button>

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
                        {{-- Aquí cargaremos con AJAX --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Modal: Detalle de un Producto --}}
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
                        {{-- Checkboxes cargados aquí --}}
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

    {{-- 4. Tabla del Carrito --}}
    <h5>Carrito</h5>
    <div class="table-responsive mb-3">
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
            <tbody></tbody>
        </table>
    </div>

    {{-- 5. Botón Finalizar Pedido --}}
    <button id="btn-finalizar" class="btn btn-success" disabled>Entregar Pedido</button>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let pagina = 1, termino = '';
            const carrito = [];
            const baseProdUrl = "{{ url('/caja/productos') }}";

            // 1) Cargar listado de productos
            function loadProductos() {
                fetch(`${baseProdUrl}?page=${pagina}&q=${encodeURIComponent(termino)}`, {
                    headers: { Accept: 'application/json' }
                })
                    .then(r => r.json())
                    .then(data => {
                        let html = '<table class="table"><thead><tr><th>Imagen</th><th>Nombre</th><th>Precio</th><th></th></tr></thead><tbody>';
                        data.data.forEach(p => {
                            html += `<tr>
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
                        // paginación centrada
                        html += `
                                    <div class="d-flex justify-content-center my-2">
                                      <nav>${data.links}</nav>
                                    </div>
                                `;
                        document.getElementById('listado-productos').innerHTML = html;
                    });
            }

            // 2) Buscar
            document.getElementById('btn-search').onclick = () => {
                termino = document.getElementById('search-productos').value.trim();
                pagina = 1;
                loadProductos();
            };

            // 3) Delegación en listado: paginación y selección
            document.getElementById('listado-productos').addEventListener('click', e => {
                // paginación
                const link = e.target.closest('a.page-link');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.getAttribute('href'), window.location.origin);
                    pagina = url.searchParams.get('page') || 1;
                    loadProductos();
                    return;
                }
                // seleccionar producto
                if (e.target.matches('.btn-seleccionar')) {
                    const id = e.target.dataset.id;
                    fetch(`${baseProdUrl}/${id}`, {
                        headers: { Accept: 'application/json' }
                    })
                        .then(r => r.json())
                        .then(p => {
                            // rellenar modalDetalle
                            document.getElementById('d-titulo').textContent = p.nombre;
                            document.getElementById('d-imagen').src = p.imagen;
                            document.getElementById('d-precio').textContent = parseFloat(p.precio).toFixed(2);
                            document.getElementById('d-cantidad').value = 1;
                            // ingredientes
                            const cont = document.getElementById('d-ingredientes');
                            cont.innerHTML = '<label>Quitar ingredientes:</label>';
                            p.ingredientes.forEach(ing => {
                                cont.innerHTML += `
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                value="${ing.id}" id="ing-${ing.id}"
                                                ${ing.es_obligatorio ? 'disabled checked' : ''}>
                                            <label class="form-check-label" for="ing-${ing.id}">
                                            ${ing.nombre} ${ing.es_obligatorio ? '(obligatorio)' : ''}
                                            </label>
                                        </div>`;
                            });
                            // guardar datos en botón
                            const btnAdd = document.getElementById('d-agregar');
                            btnAdd.dataset.id = p.id;
                            btnAdd.dataset.precio = p.precio;
                            // mostrar modal detalle
                            new bootstrap.Modal(document.getElementById('modalDetalle')).show();
                        });
                }
            });

            // 4) Agregar al carrito desde modalDetalle
            document.getElementById('d-agregar').onclick = () => {
                const btn = document.getElementById('d-agregar');
                const id = btn.dataset.id;
                const nombre = document.getElementById('d-titulo').textContent;
                const precio = parseFloat(btn.dataset.precio);
                const cantidad = parseInt(document.getElementById('d-cantidad').value, 10);

                // ingredientes quitados
                const quitados = Array.from(
                    document.querySelectorAll('#d-ingredientes .form-check-input:checked')
                )
                    .filter(ch => !ch.disabled)
                    .map(ch => {
                        const id = parseInt(ch.value, 10);
                        // obtenemos el texto del label (sin “(obligatorio)”, si lo hubiera)
                        let nombre = document.querySelector(`label[for="${ch.id}"]`).textContent;
                        nombre = nombre.replace(/\s*\(obligatorio\)$/, '').trim();
                        return { id, nombre };
                    });

                const subtotal = precio * cantidad;
                carrito.push({ id, nombre, precio, cantidad, quitados, subtotal });

                renderCarrito();
                bootstrap.Modal.getInstance(document.getElementById('modalDetalle')).hide();
            };

            // helper: renderizar carrito
            function renderCarrito() {
                const tbody = document.querySelector('#tabla-carrito tbody');
                tbody.innerHTML = '';
                carrito.forEach((item, idx) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                                    <td>${item.nombre}</td>
                                    <td>${item.quitados.length
                            ? item.quitados.map(q => q.nombre).join(', ')
                            : '<em>ninguno</em>'}</td>
                                    <td>${item.cantidad}</td>
                                    <td>$${item.subtotal.toFixed(2)}</td>
                                    <td>
                                      <button class="btn btn-sm btn-danger btn-eliminar" data-index="${idx}">
                                        &times;
                                      </button>
                                    </td>
                                `;
                    tbody.appendChild(tr);
                });
                document.getElementById('btn-finalizar').disabled = carrito.length === 0;
            }

            // 5) Eliminar del carrito
            document.querySelector('#tabla-carrito tbody').addEventListener('click', e => {
                if (e.target.matches('.btn-eliminar')) {
                    const idx = e.target.dataset.index;
                    carrito.splice(idx, 1);
                    renderCarrito();
                }
            });

            // 6) Finalizar Pedido
            document.getElementById('btn-finalizar').onclick = () => {
                const payload = {
                    items: carrito.map(item => ({
                        id: item.id,
                        cantidad: item.cantidad,
                        // enviamos sólo los IDs de quitados, no los nombres
                        quitados: item.quitados.map(q => q.id),
                    })),
                    metodo_pago: 'Efectivo'  // o el que hayas seleccionado
                };
                fetch('{{ route("caja.pedidos.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                     body: JSON.stringify(payload)
                })
                    .then(r => r.json())
                    .then(() => location.href = '{{ route("Caja.dashboard") }}');
            };

            // al abrir el modal de productos, carga la página 1
            document.getElementById('modalProductos')
                .addEventListener('shown.bs.modal', loadProductos);
        });
    </script>
@endsection