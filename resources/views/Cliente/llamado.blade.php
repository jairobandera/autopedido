@extends('layouts.app-cliente')

@section('title', 'Pedidos Entregados')

@section('content')
    <div class="container py-4">
        <h2 class="text-center mb-4">Pedidos Para Entregar</h2>
        <div id="row-pedidos" class="row g-4">
            @foreach($pedidos as $pedido)
                <div class="col-md-4 mb-4" data-id="{{ $pedido->id }}">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <span>Pedido: {{ $pedido->codigo }}</span>
                            <!-- Botón de cerrar -->
                            <button type="button" class="btn-close btn-close-white remove-card" aria-label="Quitar"></button>
                        </div>
                        <div class="card-body">
                            @if($pedido->cliente)
                                <h4>Cliente: <strong>{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</strong></h4>
                                @php $last4 = Str::substr($pedido->cliente->cedula, -4); @endphp
                                <h5>Cédula: <strong>****{{ $last4 }}</strong></h5>
                                <h5>Pedido: {{ $pedido->codigo }}</h5>
                            @else
                                <p class="text-muted">Cliente: —</p>
                                <h4 class="text-center">{{ $pedido->codigo }}</h4>
                            @endif
                        </div>
                        <div class="card-footer text-end">
                            <small class="text-muted">
                                Entregado: {{ $pedido->updated_at->format('d/m H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Fíjate que aquí dejo la URL terminando en slash:
        window.baseShowUrl = "{{ url('/cliente/pedidos') }}/";
    </script>
    @vite('resources/js/clienteWebSocket/llamado.js')
@endsection