@extends('layouts.app-caja')

@section('title', 'Dashboard Cajero')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Dashboard – Caja</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('caja.pedidos.entregados') }}"
                class="btn btn-secondary btn-lg">
                <i class="bi bi-truck"></i> Ver Entregados
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
            type="text"
            name="search"
            class="form-control"
            placeholder="Buscar por ID o código"
            value="{{ request('search') }}"
            >
            <button type="submit" class="btn btn-outline-secondary">
            <i class="bi bi-search"></i> Buscar
            </button>
        </div>
    </form>



    {{-- Tabla de pedidos --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
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
                    <tr>
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
                                @if($pedido->pago->estado === 'Completado')
                                    <span class="badge bg-success">Completado</span>
                                @elseif($pedido->pago->estado === 'Pendiente')
                                    <span class="badge bg-warning">Pendiente</span>
                                @elseif($pedido->pago->estado === 'Fallido')
                                    <span class="badge bg-danger">Fallido</span>
                                @endif
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

            // Consideramos interacción sólo al volver a la pestaña
            window.addEventListener('focus', () => {
                userHasInteracted = true;
            });

            // Poll cada 8s
            setInterval(() => {
                console.log(`🔄 Comprobando nuevos pedidos (lastId=${lastId}, lastTS=${lastTimestamp.toISOString()})`);

                fetch(checkUrl, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                })
                .then(r => r.json())
                .then(json => {
                    const serverId = json.id;
                    const serverTS = json.created_at ? new Date(json.created_at) : null;

                    // Sólo si es más alto y más reciente
                    if (serverId > lastId && serverTS && serverTS > lastTimestamp) {
                        lastId        = serverId;
                        lastTimestamp = serverTS;

                        if (userHasInteracted) {
                            sound.currentTime = 0;
                            sound.play()
                                .then(() => {
                                    sound.addEventListener('ended', () => location.reload(), { once: true });
                                })
                                .catch(() => location.reload());
                        } else {
                            location.reload();
                        }
                    }
                })
                .catch(err => console.error('Error comprobando latest:', err));
            }, 8000);
            // Ver Pedido (igual que antes)...
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
                        document.getElementById('modal-total').textContent = p.total.toFixed(2);
                        const tbody = document.getElementById('modal-detalles');
                        tbody.innerHTML = '';
                        p.detalles.forEach(d => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${d.producto.nombre}</td>
                                <td>${d.cantidad}</td>
                                <td>${parseFloat(d.subtotal).toFixed(2)}</td>`;
                            tbody.appendChild(tr);
                        });
                        document.getElementById('btn-editar-pedido').onclick = () => {
                            window.location.href = `${baseShowUrl}/${id}/edit`;
                        };
                        new bootstrap.Modal(document.getElementById('modalVerPedido')).show();
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
                fetch(`${baseShowUrl}/${id}/estado`, {
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
                sel.addEventListener('change', () => {
                    const nuevoEstado = sel.value;
                    const id = sel.dataset.id;
                    Swal.fire({
                        title: `Confirmar "${nuevoEstado}"`,
                        text: `¿Deseas marcar este pedido como "${nuevoEstado}"?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí',
                        cancelButtonText: 'No'
                    }).then(({ isConfirmed }) => {
                        if (!isConfirmed) {
                            sel.value = sel.getAttribute('data-original');
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
                        .then(res => res.ok ? res.json() : Promise.reject(res.status))
                        .then(() => {
                            const tr = sel.closest('tr');
                            const tdBadge = tr.querySelector('.td-estado');
                            let color;
                            switch (nuevoEstado) {
                                case 'Cancelado': color = 'bg-danger'; break;
                                case 'Recibido': color = 'bg-secondary'; break;
                                case 'En Preparacion': color = 'bg-warning'; break;
                                case 'Listo': color = 'bg-info'; break;
                                case 'Entregado': color = 'bg-success'; break;
                            }
                            tdBadge.innerHTML = `<span class="badge ${color}">${nuevoEstado}</span>`;
                            // ❶ re-habilito o deshabilito el botón "Cocina"
                            const cookBtn = tr.querySelector('.btn-cocina');
                            if (cookBtn) {
                            cookBtn.disabled = (nuevoEstado === 'En Preparacion');
                            }

                            if (nuevoEstado === 'Entregado') tr.remove();
                            else sel.setAttribute('data-original', nuevoEstado);
                            Swal.fire('Hecho', `Estado cambiado a "${nuevoEstado}"`, 'success');
                        })
                        .catch(() => {
                            Swal.fire('Error', 'No se pudo actualizar el estado.', 'error');
                            sel.value = sel.getAttribute('data-original');
                        });
                    });
                });
            });

        });
    </script>
@endsection
