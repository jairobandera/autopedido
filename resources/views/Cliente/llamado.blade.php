@extends('layouts.app-cliente')

@section('title', 'Pedidos Para Entregar')

@section('content')
    <div class="container py-5">
        <h2 class="text-center mb-5 fw-bold">Pedidos Para Entregar</h2>
        <div id="row-pedidos" class="row g-4">
            @foreach($pedidos as $pedido)
                <div class="col-md-4 mb-4" data-id="{{ $pedido->id }}">
                    <div class="card shadow-sm h-100 border-0 rounded-3">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center p-2">
                            <span class="fs-6">Pedido: {{ $pedido->codigo }}</span>
                            <button type="button" class="btn-close btn-close-white remove-card" aria-label="Quitar"></button>
                        </div>
                        <div class="card-body p-3">
                            @if($pedido->cliente)
                                <h4 class="card-title">Cliente: <strong>{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</strong></h4>
                                @php $last4 = Str::substr($pedido->cliente->cedula, -4); @endphp
                                <h5 class="card-subtitle mb-2 text-muted">Cédula: <strong>****{{ $last4 }}</strong></h5>
                                <h5 class="card-text">Pedido: {{ $pedido->codigo }}</h5>
                            @else
                                <p class="card-text text-muted text-center">Cliente: —</p>
                                <h4 class="card-title text-center">{{ $pedido->codigo }}</h4>
                            @endif
                        </div>
                        <div class="card-footer text-end p-2">
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
        window.baseShowUrl = "{{ url('/cliente/pedidos') }}/";
    </script>
    @vite('resources/js/clienteWebSocket/llamado.js')
@endsection