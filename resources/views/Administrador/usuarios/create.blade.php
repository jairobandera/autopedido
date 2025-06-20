@extends('layouts.app-administrador')

@section('title', 'Crear Usuario')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Crear Nuevo Usuario</h2>
            <p class="text-muted">Ingresa los datos para agregar un nuevo usuario al sistema.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>¡Ups!</strong> Hubo un problema con los datos ingresados.<br><br>
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
                <form action="{{ route('usuarios.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre de Usuario <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control"
                               placeholder="Ej: admin123" value="{{ old('nombre') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="contrasena" class="form-label fw-bold">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="contrasena" id="contrasena" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label fw-bold">Rol <span class="text-danger">*</span></label>
                        <select name="rol" id="rol" class="form-control" required>
                            <option value="" disabled {{ old('rol') ? '' : 'selected' }}>Seleccione un rol</option>
                            <option value="Administrador" {{ old('rol') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                            <option value="Cajero" {{ old('rol') == 'Cajero' ? 'selected' : '' }}>Cajero</option>
                            <option value="Cocina" {{ old('rol') == 'Cocina' ? 'selected' : '' }}>Cocina</option>
                            <option value="Cliente" {{ old('rol') == 'Cliente' ? 'selected' : '' }}>Cliente</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="bi bi-arrow-left me-1"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill">
                            <i class="bi bi-save me-1"></i> Guardar Usuario
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

    @if(session('usuario_creado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Usuario creado!',
                    text: `El usuario "${session('usuario_creado')}" se ha creado exitosamente.`,
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif
@endsection