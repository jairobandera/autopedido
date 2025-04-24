@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')
<div class="text-center mb-4">
    <h2>Editar Categoría</h2>
</div>

{{-- Errores de validación --}}
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

{{-- Formulario de edición --}}
<div class="row justify-content-center">
    <div class="col-md-6">
        <form action="{{ route('categorias.update', $categoria->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de la Categoría</label>
                <input type="text" name="nombre" id="nombre" class="form-control"
                       value="{{ old('nombre', $categoria->nombre) }}" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Volver</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    @if(session('categoria_editada'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Categoría actualizada!',
                    text: 'La categoría "{{ session('categoria_editada') }}" fue editada correctamente.',
                    confirmButtonColor: '#0d6efd',
                });
            });
        </script>
    @endif
@endsection

@if(session('categoria_duplicada'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'warning',
            title: 'Nombre duplicado',
            text: 'Ya existe una categoría con el nombre "{{ session('categoria_duplicada') }}".',
            confirmButtonColor: '#dc3545',
        });
    });
</script>
@endif

