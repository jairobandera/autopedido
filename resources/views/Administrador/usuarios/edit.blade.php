@extends('layouts.app-administrador')

@section('title', 'Editar Usuario')

@section('content')
    <div class="text-center mb-4">
        <h2>Editar Usuario</h2>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Revisá los datos ingresados:<br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de Usuario</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                           value="{{ old('nombre', $usuario->nombre) }}" required>
                </div>

                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña (dejar en blanco para no cambiar)</label>
                    <input type="password" name="contrasena" id="contrasena" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select name="rol" id="rol" class="form-control" required>
                        <option value="" disabled>Seleccione un rol</option>
                        <option value="Administrador" {{ old('rol', $usuario->rol) == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="Cajero" {{ old('rol', $usuario->rol) == 'Cajero' ? 'selected' : '' }}>Cajero</option>
                        <option value="Cocina" {{ old('rol', $usuario->rol) == 'Cocina' ? 'selected' : '' }}>Cocina</option>
                        <option value="Cliente" {{ old('rol', $usuario->rol) == 'Cliente' ? 'selected' : '' }}>Cliente</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
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
                    text: 'Ya existe un usuario con el nombre "{{ session('usuario_duplicado') }}".',
                    confirmButtonColor: '#dc3545',
                });
            });
        </script>
    @endif
@endsection