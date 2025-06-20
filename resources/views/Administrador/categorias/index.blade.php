@extends('layouts.app-administrador')

@section('title', 'Listado de Categorías')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Gestión de Categorías</h2>
            <p class="text-muted">Administra y organiza las categorías de tu catálogo.</p>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="input-group w-100 w-md-50">
                <form action="{{ route('categorias.index') }}" method="GET" class="d-flex w-100">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar categoría..."
                           value="{{ request('buscar') }}" aria-label="Buscar categoría">
                    <button type="submit" class="btn btn-primary ms-2"><i class="bi bi-search me-1"></i> Buscar</button>
                </form>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('categorias.create') }}" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i> Nueva Categoría
                </a>
                <a href="{{ route('categorias.deshabilitadas') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-eye-slash me-1"></i> Ver deshabilitados
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center rounded-3 overflow-hidden shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                        <tr class="align-middle">
                            <td>{{ $categoria->id }}</td>
                            <td>{{ $categoria->nombre }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-sm btn-warning rounded-pill">
                                        <i class="bi bi-pencil me-1"></i> Editar
                                    </a>
                                    <form id="form-eliminar-{{ $categoria->id }}"
                                          action="{{ route('categorias.destroy', $categoria->id) }}" method="POST"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                                onclick="confirmarEliminacion({{ $categoria->id }}, '{{ $categoria->nombre }}')">
                                            <i class="bi bi-slash-circle me-1"></i> Deshabilitar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-muted">
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
            <div class="d-flex justify-content-center mt-4">
                {{ $categorias->appends(['buscar' => request('buscar')])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if(session('categoria_creada'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Categoría creada!',
                    text: 'La categoría "{{ session('categoria_creada') }}" se ha creado exitosamente.',
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    @if(session('categoria_deshabilitada'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Categoría deshabilitada',
                    text: 'La categoría "{{ session('categoria_deshabilitada') }}" fue deshabilitada correctamente.',
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    @if(session('categoria_editada'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Categoría actualizada!',
                    text: 'La categoría "{{ session('categoria_editada') }}" fue editada correctamente.',
                    confirmButtonColor: '#0d6efd',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    @if(session('categoria_habilitada'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Categoría habilitada!',
                    text: 'La categoría "{{ session('categoria_habilitada') }}" fue habilitada correctamente.',
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
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