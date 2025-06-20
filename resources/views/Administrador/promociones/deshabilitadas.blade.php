@extends('layouts.app-administrador')

@section('title', 'Promociones Deshabilitadas')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Promociones Deshabilitadas</h2>
            <p class="text-muted">Revisa y habilita promociones previamente deshabilitadas.</p>
        </div>

        <a href="{{ route('promociones.index') }}" class="btn btn-outline-primary mb-4 rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center rounded-3 overflow-hidden shadow-sm">
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
                        <tr class="align-middle">
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
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i> Habilitar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted">No hay promociones deshabilitadas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
                    timer: 3000,
                    timerProgressBar: true,
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
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif
@endsection