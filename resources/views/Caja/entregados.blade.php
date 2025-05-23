@extends('layouts.app-caja')

@section('title', 'Pedidos Entregados')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Pedidos Entregados</h2>
        <a href="{{ route('Caja.dashboard') }}" class="btn btn-secondary">
            &larr; Volver al Dashboard
        </a>
    </div>

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
                            <span class="badge bg-success">Entregado</span>
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
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-ver" data-id="{{ $pedido->id }}">
                                Ver
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal Ver Pedido (idéntico al del dashboard) --}}
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
                        <tbody id="modal-detalles"></tbody>
                    </table>
                    <p class="fw-bold text-end">Total: $<span id="modal-total"></span></p>
                </div>
                <div class="modal-footer">
                    <button style="display: none;" id="btn-editar-pedido" class="btn btn-primary">Editar Pedido</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const baseShowUrl = "{{ url('/caja/pedidos') }}";

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
                                window.location.href = `${baseShowUrl}/${p.id}/edit`;
                            };
                            new bootstrap.Modal(document.getElementById('modalVerPedido')).show();
                        })
                        .catch(() => Swal.fire('Error', 'No pude cargar los detalles', 'error'));
                });
            });
        });
    </script>
@endsection