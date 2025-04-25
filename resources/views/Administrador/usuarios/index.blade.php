@extends('layouts.app-administrador')

@section('title', 'Gestión de Usuarios')

@section('content')
    <div class="text-center mb-4">
        <h2>Gestión de Usuarios</h2>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <div class="input-group w-50">
            <form action="{{ route('usuarios.index') }}" method="GET" class="d-flex w-100">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar usuario..."
                       value="{{ request('buscar') }}">
                <button type="submit" class="btn btn-outline-primary ms-2">Buscar</button>
            </form>
        </div>
        <div>
            <a href="{{ route('usuarios.create') }}" class="btn btn-success">+ Nuevo Usuario</a>
            <a href="{{ route('usuarios.deshabilitadas') }}" class="btn btn-outline-secondary ms-2">Ver deshabilitados</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
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
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->rol }}</td>
                        <td>
                            <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form id="form-deshabilitar-{{ $usuario->id }}"
                                  action="{{ route('usuarios.deshabilitar', $usuario->id) }}" method="POST"
                                  style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmarDeshabilitar({{ $usuario->id }}, '{{ $usuario->nombre }}')">
                                    Deshabilitar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $usuarios->links() }}
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
                    text: 'El usuario "{{ session('usuario_creado') }}" se ha creado exitosamente.',
                    confirmButtonColor: '#198754',
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
                    text: 'El usuario "{{ session('usuario_editado') }}" fue editado correctamente.',
                    confirmButtonColor: '#0d6efd',
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
                    text: 'El usuario "{{ session('usuario_deshabilitado') }}" fue deshabilitado correctamente.',
                    confirmButtonColor: '#198754',
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
                    text: 'El usuario "{{ session('usuario_habilitado') }}" fue habilitado correctamente.',
                    confirmButtonColor: '#198754',
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