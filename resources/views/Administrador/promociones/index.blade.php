@extends('layouts.app-administrador')

@section('title', 'Listado de Promociones')

@section('content')
    <div class="text-center mb-4">
        <h2>Gestión de Promociones</h2>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <div class="input-group w-50">
            <form action="{{ route('promociones.index') }}" method="GET" class="d-flex w-100">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar promoción..."
                    value="{{ request('buscar') }}">
                <button type="submit" class="btn btn-outline-primary ms-2">Buscar</button>
            </form>
        </div>
        <div>
            <a href="{{ route('promociones.create') }}" class="btn btn-success">+ Nueva Promoción</a>
            <a href="{{ route('promociones.deshabilitadas') }}" class="btn btn-outline-secondary ms-2">Ver
                deshabilitadas</a>
        </div>
    </div>

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
                    <th>Acciones</th>
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
                            <a href="{{ route('promociones.edit', $promo->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form id="form-eliminar-{{ $promo->id }}" action="{{ route('promociones.destroy', $promo->id) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="confirmarEliminacion({{ $promo->id }}, '{{ $promo->nombre }}')">
                                    Deshabilitar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            @if(request('buscar'))
                                No se encontraron promociones para "{{ request('buscar') }}".
                            @else
                                No hay promociones registradas.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            {{ $promociones->appends(['buscar' => request('buscar')])->links() }}
        </div>
    </div>
@endsection

@section('scripts')
    @if(session('promocion_creada'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Promoción creada!',
                    text: 'La promoción \"{{ session('promocion_creada') }}\" se ha creado correctamente.',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif

    @if(session('promocion_deshabilitada'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Promoción deshabilitada',
                    text: 'La promoción \"{{ session('promocion_deshabilitada') }}\" fue deshabilitada.',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif

    <script>
        function confirmarEliminacion(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `La promoción "${nombre}" se marcará como inactiva.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, deshabilitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-eliminar-' + id).submit();
                }
            });
        }
    </script>
@endsection