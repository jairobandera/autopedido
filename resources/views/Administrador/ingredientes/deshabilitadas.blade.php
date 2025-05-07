@extends('layouts.app-administrador')

@section('title', 'Ingredientes Deshabilitados')

@section('content')
    <div class="text-center mb-4">
        <h2>Ingredientes Deshabilitados</h2>
    </div>

    <a href="{{ route('ingredientes.index') }}" class="btn btn-primary mb-3">← Volver al listado</a>

    <div class="table-responsive">
        <table class="table table-hover text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ingredientes as $ingrediente)
                    <tr>
                        <td>{{ $ingrediente->id }}</td>
                        <td>{{ $ingrediente->nombre }}</td>
                        <td>{{ $ingrediente->descripcion }}</td>
                        <td>
                            <form id="form-habilitar-{{ $ingrediente->id }}"
                                  action="{{ route('ingredientes.habilitar', $ingrediente->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="btn btn-success btn-sm"
                                        onclick="confirmarHabilitar({{ $ingrediente->id }}, '{{ $ingrediente->nombre }}')">
                                    Habilitar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No hay ingredientes deshabilitados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $ingredientes->links() }}
        </div>
    </div>
@endsection

@section('scripts')
    @if(session('ingrediente_habilitado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Ingrediente habilitado!',
                    text: 'El ingrediente "{{ session('ingrediente_habilitado') }}" fue habilitado correctamente.',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif

    @if(session('error_habilitar'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ya existe un ingrediente activo',
                    text: 'No se puede habilitar "{{ session('error_habilitar') }}" porque ya existe activo.',
                    confirmButtonColor: '#dc3545',
                });
            });
        </script>
    @endif

    <script>
        function confirmarHabilitar(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `El ingrediente "${nombre}" será habilitado.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, habilitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-habilitar-' + id).submit();
                }
            });
        }
    </script>
@endsection