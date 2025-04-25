@extends('layouts.app-administrador')

@section('title', 'Usuarios Deshabilitados')

@section('content')
    <div class="text-center mb-4">
        <h2>Usuarios Deshabilitados</h2>
    </div>

    <a href="{{ route('usuarios.index') }}" class="btn btn-primary mb-3">← Volver al listado</a>

    <div class="table-responsive">
        <table class="table table-hover text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->rol }}</td>
                        <td>
                            <form id="form-habilitar-{{ $usuario->id }}"
                                  action="{{ route('usuarios.habilitar', $usuario->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="btn btn-success btn-sm"
                                        onclick="confirmarHabilitar({{ $usuario->id }}, '{{ $usuario->nombre }}')">
                                    Habilitar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No hay usuarios deshabilitados.</td>
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

    @if(session('error_habilitar'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ya existe un usuario activo',
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
                text: `El usuario "${nombre}" será habilitado.`,
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