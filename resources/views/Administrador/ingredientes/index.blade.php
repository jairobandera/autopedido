@extends('layouts.app-administrador')

@section('title', 'Gestión de Ingredientes')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Gestión de Ingredientes</h2>
            <p class="text-muted">Administra y organiza los ingredientes de tu catálogo.</p>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="input-group w-100 w-md-50">
                <form action="{{ route('ingredientes.index') }}" method="GET" class="d-flex w-100">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar ingrediente..."
                           value="{{ request('buscar') }}" aria-label="Buscar ingrediente">
                    <button type="submit" class="btn btn-primary ms-2 rounded-pill"><i class="bi bi-search me-1"></i> Buscar</button>
                </form>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('ingredientes.create') }}" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i> Nuevo Ingrediente
                </a>
                <a href="{{ route('ingredientes.deshabilitadas') }}" class="btn btn-outline-secondary rounded-pill px-4">
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
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingredientes as $ingrediente)
                        <tr class="align-middle">
                            <td>{{ $ingrediente->id }}</td>
                            <td>{{ $ingrediente->nombre }}</td>
                            <td>{{ $ingrediente->descripcion }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('ingredientes.edit', $ingrediente->id) }}" class="btn btn-sm btn-warning rounded-pill">
                                        <i class="bi bi-pencil me-1"></i> Editar
                                    </a>
                                    <form id="form-deshabilitar-{{ $ingrediente->id }}"
                                          action="{{ route('ingredientes.deshabilitar', $ingrediente->id) }}" method="POST"
                                          style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                                onclick="confirmarDeshabilitar({{ $ingrediente->id }}, '{{ $ingrediente->nombre }}')">
                                            <i class="bi bi-slash-circle me-1"></i> Deshabilitar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">
                                @if(request('buscar'))
                                    No se encontraron ingredientes para "{{ request('buscar') }}".
                                @else
                                    No hay ingredientes registrados.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $ingredientes->appends(['buscar' => request('buscar')])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if(session('ingrediente_creado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Ingrediente creado!',
                    text: 'El ingrediente "{{ session('ingrediente_creado') }}" se ha creado exitosamente.',
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    @if(session('ingrediente_editado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Ingrediente actualizado!',
                    text: 'El ingrediente "{{ session('ingrediente_editado') }}" fue editado correctamente.',
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    @if(session('ingrediente_deshabilitado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Ingrediente deshabilitado',
                    text: 'El ingrediente "{{ session('ingrediente_deshabilitado') }}" fue deshabilitado correctamente.',
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    @if(session('ingrediente_habilitado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Ingrediente habilitado!',
                    text: 'El ingrediente "{{ session('ingrediente_habilitado') }}" fue habilitado correctamente.',
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    <script>
        function confirmarDeshabilitar(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `El ingrediente "${nombre}" será deshabilitado.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, deshabilitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-deshabilitar-' + id).submit();
                }
            });
        }
    </script>
@endsection