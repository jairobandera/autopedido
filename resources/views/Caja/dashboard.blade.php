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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Dashboard – Caja</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('caja.pedidos.entregados') }}"
            class="btn btn-secondary btn-lg">
                <i class="bi bi-truck"></i> Ver Entregados
            </a>
            <a href="{{ url('/cliente/llamados') }}"
            class="btn btn-info btn-lg"
            target="_blank">
                <i class="bi bi-people"></i> Llamados Cliente
            </a>
            <a href="{{ route('caja.pedidos.create') }}"
            class="btn btn-primary btn-lg">
                <i class="bi bi-plus-lg"></i> Nuevo Pedido
            </a>
        </div>
    </div>

    <form method="GET" class="mb-3">
        <div class="input-group">
            <input
            id="search-input"
            type="text"
            name="search"
            class="form-control"
            placeholder="Buscar por código de pedido"
            value="{{ request('search') }}"
            autofocus
            >
            <button type="submit" class="btn btn-outline-secondary">
            <i class="bi bi-search"></i> Buscar
            </button>
        </div>
    </form>

    {{-- Tabla de pedidos --}}
    <div class="table-responsive">
        <table  id="tablaPedidos" class="table table-hover align-middle text-center">
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
                            <span class="badge bg-{{ $pedido->usuario->rol === 'Cajero' ? 'info' : 'secondary' }}">
                                {{ $pedido->usuario->rol === 'Cajero' ? 'Cajero' : 'Cliente' }}
                            </span>
                        </td>
                        <td>{{ $pedido->metodo_pago }}</td>
                        <td>{{ $pedido->codigo }}</td>
                        <td>${{ number_format($pedido->total, 2) }}</td>
                        <td class="td-estado">
                            @if($pedido->estado === 'Cancelado')
                                <span class="badge bg-danger">Cancelado</span>
                            @elseif($pedido->estado === 'Recibido')
                                <span class="badge bg-secondary">Recibido</span>
                            @elseif($pedido->estado === 'En Preparacion')
                                <span class="badge bg-warning">En Preparacion</span>
                            @elseif($pedido->estado === 'Listo')
                                <span class="badge bg-info">Listo</span>
                            @elseif($pedido->estado === 'Entregado')
                                <span class="badge bg-success">Entregado</span>
                            @endif
                        </td>
                        <td>
                            @if($pedido->pago)
                                <select
                                    class="form-select form-select-sm pago-cambio text-white"
                                    data-id="{{ $pedido->pago->id }}"
                                    style="width: auto;"
                                >
                                    @foreach(['Completado','Pendiente','Fallido'] as $ep)
                                        <option value="{{ $ep }}"
                                            {{ $pedido->pago->estado === $ep ? 'selected' : '' }}
                                        >
                                            {{ $ep }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <span class="text-muted">–</span>
                            @endif
                        </td>
                        <td class="d-flex justify-content-center gap-1">
                            <button class="btn btn-sm btn-outline-primary btn-ver" data-id="{{ $pedido->id }}">
                                Ver
                            </button>
                            {{-- botón Cocina con confirm + patch --}}
                            <button
                                class="btn btn-sm btn-outline-primary btn-cocina"
                                data-id="{{ $pedido->id }}"
                                {{ $pedido->estado === 'En Preparacion' ? 'disabled' : '' }}>
                                Cocina
                            </button>
                            {{-- select de estados sigue igual --}}
                            <select class="form-select form-select-sm me-1 estado-cambio" data-id="{{ $pedido->id }}">
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

    </table>
</div>
    {{-- Paginacion --}}
    <div class="d-flex justify-content-center mt-3">
    {{ $pedidos->links() }}
    </div>


    {{-- Modal Ver Pedido --}}
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
                    <button id="btn-editar-pedido" class="btn btn-primary">Editar Pedido</button>
                     <button id="btn-imprimir-modal" class="btn btn-secondary me-auto">
                        <i class="bi bi-printer"></i> Imprimir Comprobante
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <audio id="new-order-sound" src="{{ asset('sounds/bell.mp3') }}" preload="auto"></audio>
    <button style="display:none;" id="btn-test-sound" class="btn btn-sm btn-outline-secondary mt-2">
    </button>   

@endsection

@section('scripts')
<script>
            document.addEventListener('DOMContentLoaded', () => {
            const baseShowUrl     = "{{ url('/caja/pedidos') }}";
            const sound           = document.getElementById('new-order-sound');
            const checkUrl        = "{{ route('caja.pedidos.latest') }}";
            let lastId            = {{ $pedidos->max('id') ?? 0 }};
            let lastTimestamp = {!! $lastCreatedAt
                ? "new Date(" . json_encode($lastCreatedAt) . ")"
                : 'new Date(0)'
            !!};
            let userHasInteracted = false;

             const searchInput = document.getElementById('search-input');

            // 1) Siempre que llegue una tecla y el foco NO esté en el input,
            //    lo ponemos ahí para que el scanner escriba en ese campo.
            document.addEventListener('keydown', e => {
                if (document.activeElement !== searchInput) {
                searchInput.focus();
                // (Opcional) mover el cursor al final:
                const val = searchInput.value;
                searchInput.value = '';
                searchInput.value = val;
                }
            });

            // 2) Tu listener existente para procesar Enter…
            searchInput.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                e.preventDefault();
                e.target.form.submit();
                }
            });

            // Consideramos interacción sólo al volver a la pestaña
            window.addEventListener('focus', () => {
                userHasInteracted = true;
            });

            // Poll cada 8s
            
            // DETALLES DEL PEDIDO
           // 1) Instanciamos el modal una sola vez
            const modalEl = document.getElementById('modalVerPedido');
            const pedidoModal = new bootstrap.Modal(modalEl);

            // 2) Al ocultarse por cualquier medio, limpiamos backdrop y clase en <body>
            modalEl.addEventListener('hidden.bs.modal', () => {
                document.body.classList.remove('modal-open');
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            });

            // 3) Tu listener de “Ver pedido” igual que antes, pero usando la instancia:
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

                    // 4) Mostramos con la instancia, en lugar de crear una nueva
                    pedidoModal.show();
                })
                .catch(() => Swal.fire('Error', 'No pude cargar los detalles', 'error'));
                });
            });

            // Botón Cocina → confirm + marcar "En Preparacion"
            document.querySelectorAll('.btn-cocina').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                Swal.fire({
                title: '¿Enviar a cocina?',
                text: '¿Estás seguro de que querés marcar este pedido como "En Preparación"?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar'
                }).then(({ isConfirmed }) => {
                if (!isConfirmed) return;
                console.log('BASE URL ➔', baseShowUrl);
                console.log('FULL PATCH URL ➔', `${baseShowUrl}/${id}/estado`);
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
                    if (!res.ok) throw 0;
                    // aquí actualizamos TODO en la misma fila:
                    const tr = btn.closest('tr');

                    // 1) Badge de estado
                    const tdBadge = tr.querySelector('.td-estado');
                    tdBadge.innerHTML = `<span class="badge bg-warning">En Preparacion</span>`;

                    // 2) Select de estado
                    const sel = tr.querySelector('.estado-cambio');
                    sel.value = 'En Preparacion';
                    sel.setAttribute('data-original', 'En Preparacion');

                    // 3) Botón Cocina deshabilitado
                    btn.disabled = true;

                    Swal.fire('Listo', 'Pedido en preparación', 'success');
                })
                .catch(() => {
                    Swal.fire('Error', 'No se pudo enviar a cocina', 'error');
                });
                });
            });
            });

            // Select genérico de estado (igual que antes)...
            document.querySelectorAll('.estado-cambio').forEach(sel => {
                // Guardamos el valor original
                sel.setAttribute('data-original', sel.value);

                sel.addEventListener('change', function onChange(e) {
                // Impedimos que otros listeners capten este cambio
                e.stopImmediatePropagation();

                const nuevoEstado = this.value;
                const original    = this.getAttribute('data-original');
                const id          = this.dataset.id;

                Swal.fire({
                    title: `Confirmar "${nuevoEstado}"`,
                    text: `¿Deseas marcar este pedido como "${nuevoEstado}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí',
                    cancelButtonText: 'No'
                }).then(({ isConfirmed }) => {
                    if (!isConfirmed) {
                    // Revertimos si el usuario cancela
                    sel.value = original;
                    return;
                    }

                    // Enviamos el patch
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
                    // Actualizamos badge y habilitación de botón Cocina
                    const tr = sel.closest('tr');
                    const tdBadge = tr.querySelector('.td-estado');
                    const colorMap = {
                        Cancelado:        'bg-danger',
                        Recibido:         'bg-secondary',
                        'En Preparacion': 'bg-warning',
                        Listo:            'bg-info',
                        Entregado:        'bg-success'
                    };
                    const color = colorMap[nuevoEstado] || 'bg-secondary';
                    tdBadge.innerHTML = `<span class="badge ${color}">${nuevoEstado}</span>`;

                    const cookBtn = tr.querySelector('.btn-cocina');
                    if (cookBtn) cookBtn.disabled = (nuevoEstado === 'En Preparacion');

                    if (nuevoEstado === 'Entregado') {
                        tr.remove();
                    } else {
                        sel.setAttribute('data-original', nuevoEstado);
                    }

                    Swal.fire('Hecho', `Estado cambiado a "${nuevoEstado}"`, 'success');
                    })
                    .catch(() => {
                    // Si falla, revertimos
                    sel.value = original;
                    Swal.fire('Error', 'No se pudo actualizar el estado.', 'error');
                    });
                });
                }, /* useCapture */ true);
            });
        });

        // Cambio de estado de pago
        document.querySelectorAll('.pago-cambio').forEach(sel => {
        const colorMap = {
            Completado: 'bg-success',
            Pendiente: 'bg-warning',
            Fallido:   'bg-danger'
        };

        // Guardamos el valor original
        sel.setAttribute('data-original', sel.value);
        sel.classList.add(colorMap[sel.value] || '');

        // *** listener en captura ***
        sel.addEventListener('change', function onChange(e) {
            // frenamos cualquier otro handler de 'change'
            e.stopImmediatePropagation();

            const original = this.getAttribute('data-original');
            const nuevo    = this.value;
            const pagoId   = this.dataset.id;

            Swal.fire({
            title: `Marcar pago como "${nuevo}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'No'
            }).then(({ isConfirmed }) => {
            if (!isConfirmed) {
                // revertimos YA
                sel.value = original;
                return;
            }

            // entonces actualizamos
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
                if (!r.ok) throw new Error;
                return r.json();
            })
            .then(() => {
                // éxito: actualizamos color y data-original
                sel.classList.remove(...Object.values(colorMap));
                sel.classList.add(colorMap[nuevo] || '');
                sel.setAttribute('data-original', nuevo);
                Swal.fire('Hecho', 'Estado de pago actualizado', 'success');
            })
            .catch(() => {
                // fallo: revertimos
                sel.value = original;
                Swal.fire('Error', 'No se pudo actualizar', 'error');
            });
            });
        }, true /* <<< captura */);
        });

        {{-- Si vino un término de búsqueda y no hay pedidos, mostramos un error --}}
        @if(request('search') && $pedidos->isEmpty())
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Pedido no encontrado',
            text: `No se encontró ningún pedido con “{{ request('search') }}”`
        }).then(() => {
            // 1) Limpio la URL (quito ?search=...)
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);

            // 2) Limpio el input de búsqueda
            const input = document.getElementById('search-input');
            if (input) input.value = '';
        });
        </script>
        @endif

</script>
@endsection