@extends('layouts.app-administrador')

@section('title', 'Editar Ingrediente')

@section('content')
    <div class="text-center mb-4">
        <h2>Editar Ingrediente</h2>
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
            <form action="{{ route('ingredientes.update', $ingrediente->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Ingrediente</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                           value="{{ old('nombre', $ingrediente->nombre) }}" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" required>{{ old('descripcion', $ingrediente->descripcion) }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('ingredientes.index') }}" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
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
@endsection