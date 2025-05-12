@extends('layouts.app-administrador')

@section('title', 'Crear Promoción')

@section('content')
    <div class="text-center mb-4">
        <h2>Crear Nueva Promoción</h2>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Hubo problemas con los datos ingresados:<br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{ route('promociones.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Promoción</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required>
                </div>

                <div class="mb-3">
                    <label for="descuento" class="form-label">Descuento (%)</label>
                    <input type="number" name="descuento" id="descuento" class="form-control" value="{{ old('descuento') }}"
                        step="0.01" min="0" max="100" required>
                </div>

                <div class="mb-3">
                    <label for="codigo" class="form-label">Código (opcional)</label>
                    <input type="text" name="codigo" id="codigo" class="form-control" value="{{ old('codigo') }}">
                </div>

                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                        value="{{ old('fecha_inicio') }}">
                </div>

                <div class="mb-3">
                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('promociones.index') }}" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-success">Guardar Promoción</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @if(session('promocion_creada'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Promoción creada!',
                    text: 'La promoción \"{{ session('promocion_creada') }}\" se ha guardado correctamente.',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif
@endsection