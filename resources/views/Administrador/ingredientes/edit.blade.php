@extends('layouts.app-administrador')

@section('title', 'Editar Ingrediente')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Editar Ingrediente</h2>
            <p class="text-muted">Modifica los datos del ingrediente seleccionado.</p>
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
                <form action="{{ route('ingredientes.update', $ingrediente->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Ingrediente <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control"
                               value="{{ old('nombre', $ingrediente->nombre) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea name="descripcion" id="descripcion" class="form-control" required>{{ old('descripcion', $ingrediente->descripcion) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('ingredientes.index') }}" class="btn btn-outline-secondary rounded-pill">
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
    @if(session('ingrediente_duplicado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nombre duplicado',
                    text: 'Ya existe un ingrediente con el nombre "{{ session('ingrediente_duplicado') }}".',
                    confirmButtonColor: '#dc3545',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    @if(session('ingrediente_editado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Ingrediente actualizado!',
                    text: 'El ingrediente "{{ session('ingrediente_editado') }}" fue editado correctamente.',
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif
@endsection