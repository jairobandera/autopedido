@extends('layouts.app-administrador')

@section('title', 'Crear Ingrediente')

@section('content')
    <div class="text-center mb-4">
        <h2>Crear Nuevo Ingrediente</h2>
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
            <form action="{{ route('ingredientes.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Ingrediente</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                           placeholder="Ej: Lechuga" value="{{ old('nombre') }}" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" required>{{ old('descripcion') }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('ingredientes.index') }}" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-success">Guardar Ingrediente</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @if(session('ingrediente_duplicado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nombre duplicado',
                    text: 'Ya existe un ingrediente con el nombre "{{ session('ingrediente_duplicado') }}".',
                    confirmButtonColor: '#dc3545',
                });
            });
        </script>
    @endif

    @if(session('ingrediente_creado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Ingrediente creado!',
                    text: 'El ingrediente "{{ session('ingrediente_creado') }}" se ha creado exitosamente.',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif
@endsection