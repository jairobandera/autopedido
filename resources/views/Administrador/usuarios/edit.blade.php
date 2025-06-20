@extends('layouts.app-administrador')

@section('title', 'Editar Usuario')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Editar Usuario</h2>
            <p class="text-muted">Modifica los datos del usuario seleccionado.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>¡Ups!</strong> Revisá los datos ingresados:<br><br>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre de Usuario <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control"
                               value="{{ old('nombre', $usuario->nombre) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="contrasena" class="form-label fw-bold">Contraseña (dejar en blanco para no cambiar)</label>
                        <input type="password" name="contrasena" id="contrasena" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label fw-bold">Rol <span class="text-danger">*</span></label>
                        <select name="rol" id="rol" class="form-control" required>
                            <option value="" disabled {{ old('rol', $usuario->rol) ? '' : 'selected' }}>Seleccione un rol</option>
                            <option value="Administrador" {{ old('rol', $usuario->rol) == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                            <option value="Cajero" {{ old('rol', $usuario->rol) == 'Cajero' ? 'selected' : '' }}>Cajero</option>
                            <option value="Cocina" {{ old('rol', $usuario->rol) == 'Cocina' ? 'selected' : '' }}>Cocina</option>
                            <option value="Cliente" {{ old('rol', $usuario->rol) == 'Cliente' ? 'selected' : '' }}>Cliente</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="bi bi-arrow-left me-1"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill">
                            <i class="bi bi-save me-1"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if(session('usuario_duplicado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nombre duplicado',
                    text: `Ya existe un usuario con el nombre "${session('usuario_duplicado')}".`,
                    confirmButtonColor: '#dc3545',
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
                    text: `El usuario "${session('usuario_editado')}" fue editado correctamente.`,
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif
@endsection