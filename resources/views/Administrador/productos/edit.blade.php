@extends('layouts.app-administrador')

@section('title', 'Editar Producto')

{{-- Cargar estilos y scripts de Vite --}}
@vite([
    'resources/css/Administrador/productos/editar-producto.css',
    'resources/js/Administrador/productos/editar-producto.js'
])

@section('content')
<div class="text-center mb-4">
    <h2>Editar Producto</h2>
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
        <form action="{{ route('productos.update', $producto->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" name="nombre" id="nombre" class="form-control"
                    value="{{ old('nombre', $producto->nombre) }}" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" required>{{ old('descripcion', $producto->descripcion) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" name="precio" id="precio" class="form-control"
                    step="0.01" min="0" value="{{ old('precio', $producto->precio) }}" required>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen (URL o nombre)</label>
                <input type="text" name="imagen" id="imagen" class="form-control"
                    value="{{ old('imagen', $producto->imagen) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Previsualización de la Imagen</label>
                <div>
                    <img id="imagen-preview" src="{{ old('imagen', $producto->imagen) ?: 'https://via.placeholder.com/150' }}"
                        alt="Previsualización" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                </div>
            </div>

            <div class="mb-3">
                <label for="categoria_ids" class="form-label">Categorías (seleccione una o más)</label>
                <select name="categoria_ids[]" id="categoria_ids" class="form-control select2" multiple required>
                    @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ in_array($categoria->id, old('categoria_ids', $producto->categorias->pluck('id')->toArray())) ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="ingrediente_ids" class="form-label">Ingredientes (opcional, seleccione uno o más)</label>
                <select name="ingrediente_ids[]" id="ingrediente_ids" class="form-control select2" multiple>
                    @foreach($ingredientes as $ingrediente)
                    <option value="{{ $ingrediente->id }}" {{ in_array($ingrediente->id, old('ingrediente_ids', $producto->ingredientes->pluck('id')->toArray())) ? 'selected' : '' }}>
                        {{ $ingrediente->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Configuración de Ingredientes</label>
                <table class="table table-bordered" id="ingredientes-table">
                    <thead>
                        <tr>
                            <th>Ingrediente</th>
                            <th>Obligatorio</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>

{{-- Pasar variables PHP a JS de forma segura --}}
<script>
    window.oldIngredientesObligatorios = @json(array_keys(old('ingrediente_obligatorio', [])));
    window.existingObligatorios = @json($producto->ingredientes->where('pivot.es_obligatorio', 1)->pluck('id')->toArray());
</script>

{{-- Librerías externas --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@if(session('producto_duplicado'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Nombre duplicado',
            text: 'Ya existe un producto con el nombre "{{ session('producto_duplicado') }}".',
            confirmButtonColor: '#dc3545',
        });
    });
</script>
@endif
@endsection
