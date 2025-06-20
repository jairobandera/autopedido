@extends('layouts.app-administrador')

@section('title', 'Listado de Promociones')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Gestión de Promociones</h2>
            <p class="text-muted">Administra y organiza las promociones de tu catálogo.</p>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="input-group w-100 w-md-50">
                <form action="{{ route('promociones.index') }}" method="GET" class="d-flex w-100">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar promoción..."
                           value="{{ request('buscar') }}" aria-label="Buscar promoción">
                    <button type="submit" class="btn btn-primary ms-2"><i class="bi bi-search me-1"></i> Buscar</button>
                </form>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('promociones.create') }}" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i> Nueva Promoción
                </a>
                <a href="{{ route('promociones.deshabilitadas') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-eye-slash me-1"></i> Ver deshabilitadas
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center rounded-3 overflow-hidden shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Descuento (%)</th>
                        <th>Código</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Estado</th>
                        <th>Ver</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promociones as $promo)
                        <tr class="align-middle">
                            <td style="display:none;">{{ $promo->id }}</td>
                            <td>{{ $promo->nombre }}</td>
                            <td>{{ number_format($promo->descuento, 2) }}</td>
                            <td>{{ $promo->codigo ?? '–' }}</td>
                            <td>{{ $promo->fecha_inicio?->format('d/m/Y') ?? '–' }}</td>
                            <td>{{ $promo->fecha_fin?->format('d/m/Y') ?? '–' }}</td>
                            <td>
                                @if($promo->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info rounded-pill btn-ver-productos" data-id="{{ $promo->id }}">
                                    <i class="bi bi-eye me-1"></i> Ver
                                </button>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('promociones.edit', $promo->id) }}" class="btn btn-sm btn-warning rounded-pill">
                                        <i class="bi bi-pencil me-1"></i> Editar
                                    </a>
                                    <form id="form-eliminar-{{ $promo->id }}" action="{{ route('promociones.destroy', $promo->id) }}"
                                          method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                                onclick="confirmarEliminacion({{ $promo->id }}, '{{ $promo->nombre }}')">
                                            <i class="bi bi-slash-circle me-1"></i> Deshabilitar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted">
                                @if(request('buscar'))
                                    No se encontraron promociones para "{{ request('buscar') }}".
                                @else
                                    No hay promociones registradas.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-4">
                {{ $promociones->appends(['buscar' => request('buscar')])->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- Modal: Productos de la Promoción -->
        <div class="modal fade" id="modalVerProductos" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Productos de la Promoción</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-productos-body">
                                    <!-- se llena desde JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if(session('promocion_creada'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Promoción creada!',
                    text: 'La promoción "{{ session('promocion_creada') }}" se ha creado correctamente.',
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    @if(session('promocion_deshabilitada'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Promoción deshabilitada',
                    text: 'La promoción "{{ session('promocion_deshabilitada') }}" fue deshabilitada.',
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    <script>
        function confirmarEliminacion(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `La promoción "${nombre}" se marcará como inactiva.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, deshabilitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-eliminar-' + id).submit();
                }
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalEl = document.getElementById('modalVerProductos');
            const modal = new bootstrap.Modal(modalEl);
            const tbody = document.getElementById('modal-productos-body');
            const urlTpl = "{{ route('promociones.productos', ['promo' => ':PROMO_ID']) }}";

            document.querySelectorAll('.btn-ver-productos').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    const url = urlTpl.replace(':PROMO_ID', id);

                    // muestra “cargando…”
                    tbody.innerHTML = '<tr><td colspan="3" class="text-muted">Cargando…</td></tr>';
                    modal.show();

                    fetch(url, { headers: { 'Accept': 'application/json' } })
                        .then(r => r.ok ? r.json() : Promise.reject(r.status))
                        .then(list => {
                            if (!list.length) {
                                tbody.innerHTML = '<tr><td colspan="3"><em>No hay productos.</em></td></tr>';
                                return;
                            }
                            tbody.innerHTML = list.map(p => `
                        <tr>
                            <td>${p.id}</td>
                            <td>${p.nombre}</td>
                            <td>$${parseFloat(p.precio).toFixed(2)}</td>
                        </tr>
                    `).join('');
                        })
                        .catch(() => {
                            tbody.innerHTML = '<tr><td colspan="3"><em>Error cargando.</em></td></tr>';
                        });
                });
            });
        });
    </script>
@endsection