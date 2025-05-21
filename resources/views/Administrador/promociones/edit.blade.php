@extends('layouts.app-administrador')

@section('title', 'Editar Promoción')

@section('content')
    <div class="text-center mb-4">
        <h2>Editar Promoción</h2>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Hubo problemas con los datos ingresados:<br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form id="form-promocion" action="{{ route('promociones.update', $promo->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3 d-flex justify-content-end">
                    <button id="btn-buscar-productos" type="button" class="btn btn-primary">
                        <i class="bi bi-search"></i> + Productos
                    </button>
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Promoción</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="{{ old('nombre', $promo->nombre) }}" required>
                </div>
                <div class="mb-3">
                    <label for="descuento" class="form-label">Descuento (%)</label>
                    <input type="number" name="descuento" id="descuento" class="form-control"
                        value="{{ old('descuento', $promo->descuento) }}" step="0.01" min="0" max="100" required>
                </div>
                <div class="mb-3">
                    <label for="codigo" class="form-label">Código (opcional)</label>
                    <input type="text" name="codigo" id="codigo" class="form-control"
                        value="{{ old('codigo', $promo->codigo) }}">
                </div>
                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                        value="{{ old('fecha_inicio', $promo->fecha_inicio?->toDateString()) }}">
                </div>
                <div class="mb-3">
                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                        value="{{ old('fecha_fin', $promo->fecha_fin?->toDateString()) }}">
                </div>

                <h5>Productos Seleccionados</h5>
                <div class="table-responsive mb-3">
                    <table class="table table-sm" id="tabla-productos-seleccionados">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div class="sel-paginator mt-2"></div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('promociones.index') }}" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-primary">Actualizar Promoción</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Listado de Productos -->
    <div class="modal fade" id="modalProductos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Seleccionar Productos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <input id="search-productos" type="text" class="form-control"
                            placeholder="Buscar por nombre o ID...">
                        <button id="btn-search-productos" class="btn btn-outline-secondary">Buscar</button>
                    </div>
                    <div id="listado-productos"><!-- AJAX aquí --></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Alerta de promoción duplicada --}}
    @if(session('promocion_duplicada'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nombre duplicado',
                    text: 'Ya existe una promoción con el nombre "{{ session('promocion_duplicada') }}".',
                    confirmButtonColor: '#dc3545',
                });
            });
        </script>
    @endif

    {{-- Alerta de edición exitosa --}}
    @if(session('promocion_editada'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Promoción actualizada!',
                    text: 'La promoción "{{ session('promocion_editada') }}" se ha actualizado correctamente.',
                    confirmButtonColor: '#0d6efd',
                });
            });
        </script>
    @endif

    {{-- Todo tu JS de selección de productos abajo --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const STORAGE_KEY = 'promo_seleccionados_{{ $promo->id }}';
            const btnAbrir = document.getElementById('btn-buscar-productos');
            const modalProd = new bootstrap.Modal(document.getElementById('modalProductos'));
            const listado = document.getElementById('listado-productos');
            const btnSearch = document.getElementById('btn-search-productos');
            const searchInput = document.getElementById('search-productos');
            const tablaSelBody = document.querySelector('#tabla-productos-seleccionados tbody');
            const pagWrapper = document.querySelector('.sel-paginator');
            let pagina = 1, termino = '', selPage = 1, selPerPage = 5;

            // Cargo los productos ya asociados en el pivot
            let seleccionados = JSON.parse(localStorage.getItem(STORAGE_KEY)
                || JSON.stringify(@json($promo->productos->map(fn($p) => ['id' => $p->id, 'nombre' => $p->nombre, 'precio' => $p->precio]))));

            function saveStorage() { localStorage.setItem(STORAGE_KEY, JSON.stringify(seleccionados)); }
            function renderSeleccionados() {
                const start = (selPage - 1) * selPerPage;
                const items = seleccionados.slice(start, start + selPerPage);
                tablaSelBody.innerHTML = '';
                items.forEach((p, i) => {
                    const idx = start + i;
                    tablaSelBody.insertAdjacentHTML('beforeend', `
                            <tr>
                              <td>${p.id}</td>
                              <td>${p.nombre}</td>
                              <td>$${p.precio.toFixed(2)}</td>
                              <td>
                                <button class="btn btn-sm btn-danger btn-remove" data-index="${idx}">&times;</button>
                              </td>
                            </tr>`);
                });

                // construyo paginador
                const totalPages = Math.max(1, Math.ceil(seleccionados.length / selPerPage));
                let nav = '<ul class="pagination justify-content-center">';
                for (let i = 1; i <= totalPages; i++) {
                    nav += `<li class="page-item${i === selPage ? ' active' : ''}">
                                    <a href="#" class="page-link sel-page-link" data-page="${i}">${i}</a>
                                </li>`;
                }
                nav += '</ul>';
                pagWrapper.innerHTML = nav;
                syncHiddenInputs();
            }
            function syncHiddenInputs() {
                let container = document.getElementById('hidden-products');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'hidden-products';
                    container.style.display = 'none';
                    document.getElementById('form-promocion').appendChild(container);
                }
                container.innerHTML = '';
                seleccionados.forEach(p => {
                    container.insertAdjacentHTML('beforeend',
                        `<input type="hidden" name="products[]" value="${p.id}">`);
                });
            }

            function loadProductos() {
                fetch(`{{ route('promociones.productos.listar') }}?page=${pagina}&q=${encodeURIComponent(termino)}`, { headers: { Accept: 'application/json' } })
                    .then(r => r.json()).then(json => {
                        let html = '<table class="table"><thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Acción</th></tr></thead><tbody>';
                        json.data.forEach(p => {
                            const added = seleccionados.some(x => x.id === p.id);
                            html += `<tr>
                                    <td>${p.id}</td>
                                    <td>${p.nombre}</td>
                                    <td>$${p.precio.toFixed(2)}</td>
                                    <td>${added
                                    ? '<button class="btn btn-sm btn-secondary" disabled>✔ Añadido</button>'
                                    : `<button class="btn btn-sm btn-success btn-add"
                                                   data-id="${p.id}"
                                                   data-nombre="${p.nombre}"
                                                   data-precio="${p.precio}">
                                               Añadir
                                             </button>`}
                                    </td>
                                </tr>`;
                        });
                        html += '</tbody></table>';
                        html += `<div class="d-flex justify-content-center my-2">${json.links}</div>`;
                        listado.innerHTML = html;
                    });
            }

            // Evento abrir modal
            btnAbrir.addEventListener('click', () => {
                pagina = 1; termino = ''; loadProductos(); modalProd.show();
            });
            // Buscar
            btnSearch.addEventListener('click', () => {
                termino = searchInput.value.trim(); pagina = 1; loadProductos();
            });
            // Enter en filtro
            searchInput.addEventListener('keydown', e => {
                if (e.key === 'Enter') { e.preventDefault(); termino = searchInput.value.trim(); pagina = 1; loadProductos(); }
            });

            // Delegación clicks en modal
            listado.addEventListener('click', e => {
                // paginación
                if (e.target.closest('a.page-link')) {
                    e.preventDefault();
                    pagina = +new URL(e.target.href).searchParams.get('page');
                    loadProductos();
                }
                // añadir
                if (e.target.matches('.btn-add')) {
                    const btn = e.target;
                    const prod = { id: +btn.dataset.id, nombre: btn.dataset.nombre, precio: +btn.dataset.precio };
                    seleccionados.push(prod);
                    saveStorage(); selPage = 1; renderSeleccionados();
                    btn.closest('tr').remove();
                    Swal.fire({ icon: 'success', title: 'Añadido', toast: true, position: 'top-end', timer: 1200, showConfirmButton: false });
                }
            });

            // quitar seleccionado
            tablaSelBody.addEventListener('click', e => {
                if (e.target.matches('.btn-remove')) {
                    const idx = +e.target.dataset.index;
                    seleccionados.splice(idx, 1);
                    saveStorage();
                    selPage = Math.min(selPage, Math.ceil(seleccionados.length / selPerPage) || 1);
                    renderSeleccionados();
                }
            });
            // paginador seleccionados
            pagWrapper.addEventListener('click', e => {
                if (e.target.matches('.sel-page-link')) {
                    e.preventDefault();
                    selPage = +e.target.dataset.page;
                    renderSeleccionados();
                }
            });

            // render inicial
            renderSeleccionados();
        });
    </script>
@endsection