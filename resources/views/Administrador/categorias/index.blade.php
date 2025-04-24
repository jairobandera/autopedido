@extends('layouts.app')

@section('title', 'Listado de Categorías')

@section('content')
    <div class="text-center mb-4">
        <h2>Gestión de Categorías</h2>
    </div>

    <div class="d-flex justify-content-between mb-3">
        @if(request('buscar'))
            <div class="align-self-center">
                <span class="badge bg-info text-dark">
                    Resultados para: "{{ request('buscar') }}"
                </span>
                <a href="{{ route('categorias.index') }}" class="btn btn-sm btn-outline-secondary ms-2">Limpiar búsqueda</a>
            </div>
        @endif

        <a href="{{ route('categorias.create') }}" class="btn btn-success">+ Nueva Categoría</a>
        <a href="{{ route('categorias.deshabilitadas') }}" class="btn btn-outline-secondary ms-2">Ver deshabilitadas</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->id }}</td>
                        <td>{{ $categoria->nombre }}</td>
                        <td>
                            <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form id="form-eliminar-{{ $categoria->id }}"
                                  action="{{ route('categorias.destroy', $categoria->id) }}"
                                  method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmarEliminacion({{ $categoria->id }}, '{{ $categoria->nombre }}')">
                                    Deshabilitar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            @if(request('buscar'))
                                No se encontraron categorías para "{{ request('buscar') }}".
                            @else
                                No hay categorías registradas.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    @if(session('categoria_creada'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Categoría creada!',
                    text: 'La categoría "{{ session('categoria_creada') }}" se ha creado exitosamente.',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif

    @if(session('categoria_deshabilitada'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Categoría deshabilitada',
                    text: 'La categoría "{{ session('categoria_deshabilitada') }}" fue deshabilitada correctamente.',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif

    @if(session('categoria_editada'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Categoría actualizada!',
                    text: 'La categoría "{{ session('categoria_editada') }}" fue editada correctamente.',
                    confirmButtonColor: '#0d6efd',
                });
            });
        </script>
    @endif

    <script>
        function confirmarEliminacion(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `La categoría "${nombre}" será deshabilitada.`,
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
