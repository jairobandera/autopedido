@extends('layouts.app-administrador')

@section('title', 'Promociones Deshabilitadas')

@section('content')
    <div class="text-center mb-4">
        <h2>Promociones Deshabilitadas</h2>
    </div>

    <a href="{{ route('promociones.index') }}" class="btn btn-primary mb-3">← Volver al listado</a>

    <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descuento (%)</th>
                    <th>Código</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promociones as $promo)
                    <tr>
                        <td>{{ $promo->id }}</td>
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
                            <form action="{{ route('promociones.habilitar', $promo->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-success btn-sm">Habilitar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No hay promociones deshabilitadas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    @if(session('error_habilitar'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'No se puede habilitar',
                    text: 'Ya existe una promoción activa con el nombre "{{ session('error_habilitar') }}".',
                    confirmButtonColor: '#dc3545',
                });
            });
        </script>
    @endif

    @if(session('promocion_habilitada'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Promoción habilitada!',
                    text: 'La promoción "{{ session('promocion_habilitada') }}" fue habilitada correctamente.',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif
@endsection