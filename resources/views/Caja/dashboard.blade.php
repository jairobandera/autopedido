@extends('layouts.app-caja')

@section('title', 'Dashboard Cajero')

<script>
    window.baseShowUrl = "{{ url('/caja/pedidos') }}";
    window.basePagoUrl = "{{ url('/caja/pagos') }}";
    window.csrfToken = "{{ csrf_token() }}";
    console.log('Injected BASE URL ➔', window.baseShowUrl);
</script>
@vite('resources/js/cajaWebSocket/dashboard.js')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Dashboard – Caja</h2>
        <div class="d-flex gap-3">
            <a href="{{ route('caja.pedidos.entregados') }}" class="btn btn-secondary btn-lg rounded-pill">
                <i class="bi bi-truck me-1"></i> Ver Entregados
            </a>
            <a href="{{ url('/cliente/llamados') }}" class="btn btn-info btn-lg rounded-pill" target="_blank">
                <i class="bi bi-people me-1"></i> Llamados Cliente
            </a>
            <a href="{{ route('caja.pedidos.create') }}" class="btn btn-primary btn-lg rounded-pill">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Pedido
            </a>
        </div>
    </div>

    <form method="GET" role="search" class="mb-4">
    <div class="input-group">
        <input 
            id="search-input"
            type="text"
            name="search"
            class="form-control rounded-start"
            placeholder="Buscar por código de pedido"
            value="{{ request('search') }}"
            autocomplete="off"
            autofocus
        >
        <button type="submit" class="btn btn-outline-secondary rounded-end">
            <i class="bi bi-search me-1"></i> Buscar
        </button>
    </div>
</form>


    <!-- Tabla de pedidos -->
    <div class="table-responsive">
        <table id="tablaPedidos" class="table table-hover align-middle text-center rounded-3 overflow-hidden shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Origen</th>
                    <th>Método</th>
                    <th>Código</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Estado Pago</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedidos as $pedido)
                    <tr class="pedido-card" data-id="{{ $pedido->id }}">
                        <td>{{ $pedido->id }}</td>
                        <td>
                            <span class="badge {{ $pedido->usuario->rol === 'Cajero' ? 'bg-info' : 'bg-secondary' }} text-white">
                                {{ $pedido->usuario->rol === 'Cajero' ? 'Cajero' : 'Cliente' }}
                            </span>
                        </td>
                        <td>{{ $pedido->metodo_pago }}</td>
                        <td>{{ $pedido->codigo }}</td>
                        <td>${{ number_format($pedido->total, 2, ',', '.') }}</td>
                        <td class="td-estado">
                            @switch($pedido->estado)
                                @case('Cancelado')
                                    <span class="badge bg-danger text-white">Cancelado</span>
                                    @break
                                @case('Recibido')
                                    <span class="badge bg-secondary text-white">Recibido</span>
                                    @break
                                @case('En Preparacion')
                                    <span class="badge bg-warning text-white">En Preparacion</span>
                                    @break
                                @case('Listo')
                                    <span class="badge bg-info text-white">Listo</span>
                                    @break
                                @case('Entregado')
                                    <span class="badge bg-success text-white">Entregado</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary text-white">Desconocido</span>
                            @endswitch
                        </td>
                        <td>
                            @if($pedido->pago)
                                <select class="form-select form-select-sm pago-cambio me-1 rounded-pill" data-id="{{ $pedido->pago->id }}">
                                    @foreach(['Completado', 'Pendiente', 'Fallido'] as $ep)
                                        <option value="{{ $ep }}" {{ $pedido->pago->estado === $ep ? 'selected' : '' }}>
                                            {{ $ep }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <span class="text-muted">–</span>
                            @endif
                        </td>
                        <td class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-outline-primary btn-ver rounded-pill" data-id="{{ $pedido->id }}">
                                <i class="bi bi-eye me-1"></i> Ver
                            </button>
                            <button class="btn btn-sm btn-outline-primary btn-cocina rounded-pill"
                                    data-id="{{ $pedido->id }}"
                                    {{ $pedido->estado === 'En Preparacion' ? 'disabled' : '' }}>
                                <i class="bi bi-fire me-1"></i> Cocina
                            </button>
                            <select class="form-select form-select-sm estado-cambio me-1 rounded-pill" data-id="{{ $pedido->id }}">
                                @foreach(['Cancelado', 'Recibido', 'En Preparacion', 'Listo', 'Entregado'] as $e)
                                    <option value="{{ $e }}" {{ $pedido->estado === $e ? 'selected' : '' }}>
                                        {{ $e }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $pedidos->links('pagination::bootstrap-5') }}
    </div>

    <!-- Modal Ver Pedido -->
    <div class="modal fade" id="modalVerPedido" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle Pedido <span id="modal-codigo"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modal-detalles">
                            <!-- se carga vía JS -->
                        </tbody>
                    </table>
                    <p class="fw-bold text-end">Total: $<span id="modal-total"></span></p>
                </div>
                <div class="modal-footer">
                    <button id="btn-editar-pedido" class="btn btn-primary rounded-pill">Editar Pedido</button>
                    <button id="btn-imprimir-modal" class="btn btn-secondary rounded-pill me-auto">
                        <i class="bi bi-printer me-1"></i> Imprimir Comprobante
                    </button>
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <audio id="new-order-sound" src="{{ asset('sounds/bell.mp3') }}" preload="auto"></audio>
    <button style="display:none;" id="btn-test-sound" class="btn btn-sm btn-outline-secondary mt-2"></button>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const baseShowUrl = "{{ url('/caja/pedidos') }}";
        const sound = document.getElementById('new-order-sound');
        const checkUrl = "{{ route('caja.pedidos.latest') }}";
        let lastId = {{ $pedidos->max('id') ?? 0 }};
        let lastTimestamp = {!! $lastCreatedAt
            ? "new Date(" . json_encode($lastCreatedAt) . ")"
            : 'new Date(0)'
        !!};
        let userHasInteracted = false;

        const searchInput = document.getElementById('search-input');

        // 1) Redirigir foco al input si no está activo
        document.addEventListener('keydown', e => {
            if (document.activeElement !== searchInput) {
                searchInput.focus();
                const val = searchInput.value;
                searchInput.value = '';
                searchInput.value = val;
            }
        });

        // 2) Enviar formulario al presionar Enter
        searchInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                e.target.form.submit();
            }
        });

        // 3) Detectar interacción al volver a la pestaña
        window.addEventListener('focus', () => {
            userHasInteracted = true;
        });

        // 4) Modal de detalles
        const modalEl = document.getElementById('modalVerPedido');
        const pedidoModal = new bootstrap.Modal(modalEl);

        modalEl.addEventListener('hidden.bs.modal', () => {
            document.body.classList.remove('modal-open');
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        });

        document.querySelectorAll('.btn-ver').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                fetch(`${baseShowUrl}/${id}`, {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.ok ? res.json() : Promise.reject(res.status))
                .then(p => {
                    document.getElementById('modal-codigo').textContent = p.codigo;
                    document.getElementById('modal-total').textContent = parseFloat(p.total).toFixed(2);
                    const tbody = document.getElementById('modal-detalles');
                    tbody.innerHTML = '';
                    p.detalles.forEach(d => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${d.producto.nombre}</td>
                            <td>${d.cantidad}</td>
                            <td>$${parseFloat(d.subtotal).toFixed(2)}</td>`;
                        tbody.appendChild(tr);
                    });
                    document.getElementById('btn-editar-pedido').onclick = () => {
                        window.location.href = `${baseShowUrl}/${id}/edit`;
                    };
                    document.getElementById('btn-imprimir-modal').onclick = () => {
                        window.open(`/caja/pedidos/${id}/comprobante`, '_blank');
                    };
                    pedidoModal.show();
                })
                .catch(() => Swal.fire('Error', 'No pude cargar los detalles', 'error'));
            });
        });

        // 5) Botón Cocina
        document.querySelectorAll('.btn-cocina').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                Swal.fire({
                    title: '¿Enviar a cocina?',
                    text: '¿Estás seguro de que querés marcar este pedido como "En Preparación"?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, enviar',
                    cancelButtonText: 'Cancelar'
                }).then(({ isConfirmed }) => {
                    if (!isConfirmed) return;
                    fetch(`${window.baseShowUrl}/${id}/estado`, {
                        method: 'PATCH',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ estado: 'En Preparacion' })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Error en la solicitud');
                        const tr = btn.closest('tr');
                        const tdBadge = tr.querySelector('.td-estado');
                        tdBadge.innerHTML = '<span class="badge bg-warning text-white">En Preparacion</span>';
                        const sel = tr.querySelector('.estado-cambio');
                        sel.value = 'En Preparacion';
                        sel.setAttribute('data-original', 'En Preparacion');
                        btn.disabled = true;
                        Swal.fire('Listo', 'Pedido en preparación', 'success');
                    })
                    .catch(() => {
                        Swal.fire('Error', 'No se pudo enviar a cocina', 'error');
                    });
                });
            });
        });

        // 6) Cambio de estado
        document.querySelectorAll('.estado-cambio').forEach(sel => {
            sel.setAttribute('data-original', sel.value);
            sel.addEventListener('change', function(e) {
                e.stopImmediatePropagation();
                const nuevoEstado = this.value;
                const original = this.getAttribute('data-original');
                const id = this.dataset.id;

                Swal.fire({
                    title: `Confirmar "${nuevoEstado}"`,
                    text: `¿Deseas marcar este pedido como "${nuevoEstado}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí',
                    cancelButtonText: 'No'
                }).then(({ isConfirmed }) => {
                    if (!isConfirmed) {
                        sel.value = original;
                        return;
                    }
                    fetch(`${baseShowUrl}/${id}/estado`, {
                        method: 'PATCH',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ estado: nuevoEstado })
                    })
                    .then(res => res.ok ? res.json() : Promise.reject())
                    .then(() => {
                        const tr = sel.closest('tr');
                        const tdBadge = tr.querySelector('.td-estado');
                        const colorMap = {
                            'Cancelado': 'bg-danger',
                            'Recibido': 'bg-secondary',
                            'En Preparacion': 'bg-warning',
                            'Listo': 'bg-info',
                            'Entregado': 'bg-success'
                        };
                        const color = colorMap[nuevoEstado] || 'bg-secondary';
                        tdBadge.innerHTML = `<span class="badge ${color} text-white">${nuevoEstado}</span>`;
                        const cookBtn = tr.querySelector('.btn-cocina');
                        cookBtn.disabled = (nuevoEstado === 'En Preparacion');
                        if (nuevoEstado === 'Entregado') {
                            tr.remove();
                        } else {
                            sel.setAttribute('data-original', nuevoEstado);
                        }
                        Swal.fire('Hecho', `Estado cambiado a "${nuevoEstado}"`, 'success');
                    })
                    .catch(() => {
                        sel.value = original;
                        Swal.fire('Error', 'No se pudo actualizar el estado.', 'error');
                    });
                }, true);
            });
        });

        // 7) Cambio de estado de pago
        document.querySelectorAll('.pago-cambio').forEach(sel => {
            const colorMap = {
                'Completado': 'bg-success',
                'Pendiente': 'bg-warning',
                'Fallido': 'bg-danger'
            };
            sel.setAttribute('data-original', sel.value);
            sel.classList.add(colorMap[sel.value] || '');

            sel.addEventListener('change', function(e) {
                e.stopImmediatePropagation();
                const original = this.getAttribute('data-original');
                const nuevo = this.value;
                const pagoId = this.dataset.id;

                Swal.fire({
                    title: `Marcar pago como "${nuevo}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí',
                    cancelButtonText: 'No'
                }).then(({ isConfirmed }) => {
                    if (!isConfirmed) {
                        sel.value = original;
                        return;
                    }
                    fetch(`/caja/pagos/${pagoId}/estado`, {
                        method: 'PATCH',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ estado: nuevo })
                    })
                    .then(r => {
                        if (!r.ok) throw new Error('Error en la solicitud');
                        return r.json();
                    })
                    .then(() => {
                        sel.classList.remove(...Object.values(colorMap));
                        sel.classList.add(colorMap[nuevo] || '');
                        sel.setAttribute('data-original', nuevo);
                        Swal.fire('Hecho', 'Estado de pago actualizado', 'success');
                    })
                    .catch(() => {
                        sel.value = original;
                        Swal.fire('Error', 'No se pudo actualizar', 'error');
                    });
                }, true);
            });
        });

        // 8) Manejo de búsqueda sin resultados
        @if(request('search') && $pedidos->isEmpty())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Pedido no encontrado',
                    text: `No se encontró ningún pedido con “{{ request('search') }}”`
                }).then(() => {
                    const cleanUrl = window.location.origin + window.location.pathname;
                    window.history.replaceState({}, document.title, cleanUrl);
                    const input = document.getElementById('search-input');
                    if (input) input.value = '';
                });
            </script>
        @endif
    </script>
@endsection