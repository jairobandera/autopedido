@extends('layouts.app-cocina')

@section('title', 'Dashboard Cocina')

@section('content')
  <div class="container">
    <h2 class="mb-4 text-center">Pedidos</h2>
    <div id="alert-vacio" class="alert alert-info d-none">
    No hay pedidos en preparación.
    </div>
    <div id="row-pedidos" class="row g-4">
    @foreach($pedidos as $pedido)
    <div class="col-md-4" data-id="{{ $pedido->id }}">
      <div class="card h-100 shadow-sm">
      <div class="card-header bg-warning text-white">
      Pedido #{{ $loop->iteration }}
      </div>
      <div class="card-body" style="max-height:200px;overflow-y:auto;">
      <ul class="list-unstyled mb-0">
      @foreach($pedido->detalles as $det)
      <li class="mb-3 pb-2" style="border-bottom:1px solid rgba(0,0,0,0.1);">
      <div class="d-flex justify-content-between">
      <strong>{{ $det->producto->nombre }}</strong>
      <span>x {{ $det->cantidad }}</span>
      </div>
      @if($det->ingredientesQuitados->isNotEmpty())
      <div class="small text-danger mt-1">
      Sin: {{ $det->ingredientesQuitados->pluck('nombre')->join(', ') }}
      </div>
      @endif
      </li>
      @endforeach
      </ul>
      </div>
      <div class="card-footer d-flex justify-content-between align-items-center">
      <small class="text-muted">
      Solicitado: {{ $pedido->created_at->translatedFormat('d/m, h:mm a') }}
      </small>
      <button class="btn btn-sm btn-success btn-listo" data-id="{{ $pedido->id }}">
      <i class="bi bi-check-circle"></i> Listo
      </button>
      </div>
      </div>
    </div>
    @endforeach
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    window.baseCocinaUrl = "{{ url('/cocina/pedidos') }}";
    window.csrfToken = "{{ csrf_token() }}";
    console.log('Cocina BASE URL ➔', window.baseCocinaUrl);
  </script>
  @vite('resources/js/cocinaWebSocket/dashboard.js')
@endsection