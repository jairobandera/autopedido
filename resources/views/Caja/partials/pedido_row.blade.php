<!--//Este archivo es para que cuando el cliente realiza un pedido
//se cargue el pedido con los mismos colores y estilos que todas las demas filas de pedidos
//en el daashboard del cajero-->

<tr>
    <td class="pedido-card">{{ $pedido->id }}</td>
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
            {{-- Aquí agregamos condicionales para el fondo del select --}}
            @php
                $colorPago = match ($pedido->pago->estado) {
                    'Completado' => 'bg-success',
                    'Pendiente' => 'bg-warning',
                    'Fallido' => 'bg-danger',
                    default => 'bg-secondary',
                };
            @endphp
            <select class="form-select form-select-sm pago-cambio text-white {{ $colorPago }}"
                data-id="{{ $pedido->pago->id }}" style="width: auto;">
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
    <td class="d-flex justify-content-center gap-1">
        <button class="btn btn-sm btn-outline-primary btn-ver" data-id="{{ $pedido->id }}">
            Ver
        </button>
        <button class="btn btn-sm btn-outline-primary btn-cocina" data-id="{{ $pedido->id }}" {{ $pedido->estado === 'En Preparacion' ? 'disabled' : '' }}>
            Cocina
        </button>
        <select class="form-select form-select-sm me-1 estado-cambio" data-id="{{ $pedido->id }}">
            @foreach(['Cancelado', 'Recibido', 'En Preparacion', 'Listo', 'Entregado'] as $e)
                <option value="{{ $e }}" {{ $pedido->estado === $e ? 'selected' : '' }}>
                    {{ $e }}
                </option>
            @endforeach
        </select>
    </td>
</tr>