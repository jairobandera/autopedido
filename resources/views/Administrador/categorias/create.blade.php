@extends('layouts.app')

@section('title', 'Crear Categoría')

@section('content')
<div class="text-center mb-4">
    <h2>Crear Nueva Categoría</h2>
</div>

{{-- Mostrar errores de validación --}}
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

{{-- Formulario de creación --}}
<div class="row justify-content-center">
    <div class="col-md-6">
        <form action="{{ route('categorias.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de la Categoría</label>
                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Bebidas" value="{{ old('nombre') }}" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Volver</a>
                <button type="submit" class="btn btn-success">Guardar Categoría</button>
            </div>
        </form>
    </div>
</div>
@endsection

@if(session('categoria_creada'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: '¡Categoría creada!',
            text: 'La categoría "{{ session('categoria_creada') }}" se ha creado exitosamente.',
            confirmButtonColor: '#198754',
        });
    });
</script>
@endif

@if(session('categoria_duplicada'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'warning',
            title: 'Categoría duplicada',
            text: 'La categoría "{{ session('categoria_duplicada') }}" ya existe.',
            confirmButtonColor: '#dc3545',
        });
    });
</script>
@endif


