@extends('layouts.app-administrador')

@section('title', 'Gestión de Usuarios')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Gestión de Usuarios</h2>
            <p class="text-muted">Administra y organiza los usuarios de tu sistema.</p>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="input-group w-100 w-md-50">
                <form action="{{ route('usuarios.index') }}" method="GET" class="d-flex w-100">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar usuario..."
                           value="{{ request('buscar') }}" aria-label="Buscar usuario">
                    <button type="submit" class="btn btn-primary ms-2 rounded-pill"><i class="bi bi-search me-1"></i> Buscar</button>
                </form>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('usuarios.create') }}" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i> Nuevo Usuario
                </a>
                <a href="{{ route('usuarios.deshabilitadas') }}" class="btn btn-outline-secondary rounded-pill px-4">
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
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr class="align-middle">
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->nombre }}</td>
                            <td>{{ $usuario->rol }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-warning rounded-pill">
                                        <i class="bi bi-pencil me-1"></i> Editar
                                    </a>
                                    <form id="form-deshabilitar-{{ $usuario->id }}"
                                          action="{{ route('usuarios.deshabilitar', $usuario->id) }}" method="POST"
                                          style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                                onclick="confirmarDeshabilitar({{ $usuario->id }}, '{{ $usuario->nombre }}')">
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
                                    No se encontraron usuarios para "{{ request('buscar') }}".
                                @else
                                    No hay usuarios registrados.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $usuarios->appends(['buscar' => request('buscar')])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if(session('usuario_creado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Usuario creado!',
                    text: "El usuario '{{ session('usuario_creado') }}' se ha creado exitosamente.",
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    @if(session('usuario_editado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Usuario actualizado!',
                    text: "El usuario '{{ session('usuario_editado') }}' fue editado correctamente.",
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    @if(session('usuario_deshabilitado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Usuario deshabilitado',
                    text: "El usuario '{{ session('usuario_deshabilitado') }}' fue deshabilitado correctamente.",
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    @if(session('usuario_habilitado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Usuario habilitado!',
                    text: "El usuario '{{ session('usuario_habilitado') }}' fue habilitado correctamente.",
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
                text: `El usuario "${nombre}" será deshabilitado.`,
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