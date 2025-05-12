@extends('layouts.app-administrador')

@section('title', 'Editar Promoción')

@section('content')
    <div class="text-center mb-4">
        <h2>Editar Promoción</h2>
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
            <form action="{{ route('promociones.update', $promo->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Promoción</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="{{ old('nombre', $promo->nombre) }}" required>
                </div>

                <div class="mb-3">
                    <label for="descuento" class="form-label">Descuento (%)</label>
                    <input type="number" name="descuento" id="descuento" class="form-control"
                        value="{{ old('descuento', $promo->descuento) }}" step="0.01" min="0" max="100" required>
                </div>

                <div class="mb-3">
                    <label for="codigo" class="form-label">Código (opcional)</label>
                    <input type="text" name="codigo" id="codigo" class="form-control"
                        value="{{ old('codigo', $promo->codigo) }}">
                </div>

                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                        value="{{ old('fecha_inicio', $promo->fecha_inicio?->toDateString()) }}">
                </div>

                <div class="mb-3">
                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                        value="{{ old('fecha_fin', $promo->fecha_fin?->toDateString()) }}">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('promociones.index') }}" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-primary">Actualizar Promoción</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @if(session('promocion_editada'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Promoción actualizada!',
                    text: 'La promoción "{{ session('promocion_editada') }}" fue editada correctamente.',
                    confirmButtonColor: '#0d6efd',
                });
            });
        </script>
    @endif

    @if(session('promocion_duplicada'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nombre duplicado',
                    text: 'Ya existe una promoción con el nombre "{{ session('promocion_duplicada') }}".',
                    confirmButtonColor: '#dc3545',
                });
            });
        </script>
    @endif
@endsection