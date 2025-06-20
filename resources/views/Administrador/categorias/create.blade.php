@extends('layouts.app-administrador')

@section('title', 'Crear Categoría')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Crear Nueva Categoría</h2>
            <p class="text-muted">Ingresa los datos para agregar una nueva categoría a tu catálogo.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>¡Ups!</strong> Hubo un problema con los datos ingresados.<br><br>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('categorias.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Categoría <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Bebidas" value="{{ old('nombre') }}" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="bi bi-arrow-left me-1"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill">
                            <i class="bi bi-save me-1"></i> Guardar Categoría
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if(session('categoria_creada'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Categoría creada!',
                    text: 'La categoría "{{ session('categoria_creada') }}" se ha creado exitosamente.',
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
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
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif
@endsection