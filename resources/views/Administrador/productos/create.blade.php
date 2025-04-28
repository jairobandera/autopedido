@extends('layouts.app-administrador')

@section('title', 'Crear Producto')

@section('content')
    <div class="text-center mb-4">
        <h2>Crear Nuevo Producto</h2>
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
            <form action="{{ route('productos.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Producto</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                           placeholder="Ej: Hamburguesa" value="{{ old('nombre') }}" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" required>{{ old('descripcion') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" name="precio" id="precio" class="form-control"
                           step="0.01" min="0" value="{{ old('precio') }}" required>
                </div>

                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen (URL o nombre)</label>
                    <input type="text" name="imagen" id="imagen" class="form-control"
                           placeholder="Ej: hamburguesa.jpg" value="{{ old('imagen') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Previsualización de la Imagen</label>
                    <div>
                        <img id="imagen-preview" src="{{ old('imagen') ?: 'https://via.placeholder.com/150' }}"
                             alt="Previsualización" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="categoria_ids" class="form-label">Categorías (seleccione una o más)</label>
                    <select name="categoria_ids[]" id="categoria_ids" class="form-control select2" multiple required>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ in_array($categoria->id, old('categoria_ids', [])) ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="ingrediente_ids" class="form-label">Ingredientes (opcional, seleccione uno o más)</label>
                    <select name="ingrediente_ids[]" id="ingrediente_ids" class="form-control select2" multiple>
                        @foreach($ingredientes as $ingrediente)
                            <option value="{{ $ingrediente->id }}" {{ in_array($ingrediente->id, old('ingrediente_ids', [])) ? 'selected' : '' }}>
                                {{ $ingrediente->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-success">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 0.375rem 0.75rem;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding: 0;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #198754;
            color: white;
            border: none;
            padding: 2px 5px;
            margin: 2px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#categoria_ids').select2({
                placeholder: 'Seleccione una o más categorías',
                allowClear: true,
                width: '100%'
            });
            $('#ingrediente_ids').select2({
                placeholder: 'Seleccione uno o más ingredientes',
                allowClear: true,
                width: '100%'
            });

            const imagenInput = document.getElementById('imagen');
            const imagenPreview = document.getElementById('imagen-preview');
            imagenInput.addEventListener('input', function () {
                const url = imagenInput.value.trim();
                imagenPreview.src = url || 'https://via.placeholder.com/150';
            });
        });
    </script>

    @if(session('producto_duplicado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nombre duplicado',
                    text: 'Ya existe un producto con el nombre "{{ session('producto_duplicado') }}".',
                    confirmButtonColor: '#dc3545',
                });
            });
        </script>
    @endif

    @if(session('producto_creado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Producto creado!',
                    text: 'El producto "{{ session('producto_creado') }}" se ha creado exitosamente.',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif
@endsection