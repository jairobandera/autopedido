@extends('layouts.app-administrador')

@section('title', 'Usuarios Deshabilitados')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Usuarios Deshabilitados</h2>
            <p class="text-muted">Revisa y habilita usuarios previamente deshabilitados.</p>
        </div>

        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-primary mb-4 rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center rounded-3 overflow-hidden shadow-sm">
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
                        <tr class="align-middle">
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->nombre }}</td>
                            <td>{{ $usuario->rol }}</td>
                            <td>
                                <form id="form-habilitar-{{ $usuario->id }}"
                                      action="{{ route('usuarios.habilitar', $usuario->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" class="btn btn-success btn-sm rounded-pill"
                                            onclick="confirmarHabilitar({{ $usuario->id }}, '{{ $usuario->nombre }}')">
                                        <i class="bi bi-check-circle me-1"></i> Habilitar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">No hay usuarios deshabilitados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $usuarios->links('pagination::bootstrap-5') }}
            </div>
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
                    text: "El usuario '{{ session('usuario_habilitado') }}' fue habilitado correctamente.",
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
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
                    text: "No se puede habilitar '{{ session('error_habilitar') }}' porque ya existe activo.",
                    confirmButtonColor: '#dc3545',
                    timer: 3000,
                    timerProgressBar: true,
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