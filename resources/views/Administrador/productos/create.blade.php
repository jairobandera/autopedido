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
                        <img id="imagen-preview" src="{{ old('imagen') ?: 'https://cdn-icons-png.flaticon.com/512/10446/10446694.png' }}"
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
                    <button type="submit" class="btn btn-success">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cargar CSS y JS externos -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @vite(['resources/css/Administrador/productos/crear-producto.css'])

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/Administrador/productos/crear-producto.js'])

    <!-- Pasar datos de sesión al JavaScript -->
    <script>
        // Convertir old('ingrediente_obligatorio') en una lista de IDs marcados como obligatorios
        window.oldIngredientesObligatorios = @json(collect(old('ingrediente_obligatorio', []))->keys()->toArray());
        @if(session('producto_duplicado'))
            window.productoDuplicado = {{ json_encode(session('producto_duplicado')) }};
        @endif
        @if(session('productocreado'))
            window.productoCreado = {{ json_encode(session('productocreado')) }};
        @endif
    </script>
@endsection