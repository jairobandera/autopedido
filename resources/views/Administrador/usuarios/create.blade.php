@extends('layouts.app-administrador')

@section('title', 'Crear Usuario')

@section('content')

<script>
    window.usuarioDuplicado = @json(session('usuario_duplicado'));
    window.usuarioCreado = @json(session('usuario_creado'));
</script>

<style>
    .form-label {
        font-weight: bold;
    }
</style>

<div class="text-center mb-4">
    <h2>Crear Nuevo Usuario</h2>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Ups!</strong> Hubo un problema con los datos ingresados.<br><br>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row justify-content-center">
    <div class="col-md-6">
        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de Usuario</label>
                <input type="text" name="nombre" id="nombre" class="form-control"
                    placeholder="Ej: admin123" value="{{ old('nombre') }}" required>
            </div>

            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" name="contrasena" id="contrasena" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select name="rol" id="rol" class="form-control" required>
                    <option value="" disabled {{ old('rol') ? '' : 'selected' }}>Seleccione un rol</option>
                    <option value="Administrador" {{ old('rol') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                    <option value="Cajero" {{ old('rol') == 'Cajero' ? 'selected' : '' }}>Cajero</option>
                    <option value="Cocina" {{ old('rol') == 'Cocina' ? 'selected' : '' }}>Cocina</option>
                    <option value="Cliente" {{ old('rol') == 'Cliente' ? 'selected' : '' }}>Cliente</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
                <button type="submit" class="btn btn-success">Guardar Usuario</button>
            </div>
        </form>
    </div>
</div>

<!-- Librería SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.usuarioCreado) {
            Swal.fire({
                icon: 'success',
                title: '¡Usuario creado!',
                text: `El usuario "${window.usuarioCreado}" se ha creado exitosamente.`,
                confirmButtonColor: '#198754',
            });
        }

        if (window.usuarioDuplicado) {
            Swal.fire({
                icon: 'warning',
                title: 'Nombre duplicado',
                text: `Ya existe un usuario con el nombre "${window.usuarioDuplicado}".`,
                confirmButtonColor: '#dc3545',
            });
        }
    });
</script>
@endsection