@extends('layouts.app-administrador')

@section('title', 'Crear Producto')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Crear Nuevo Producto</h2>
            <p class="text-muted">Completa los datos para agregar un nuevo producto a tu catálogo.</p>
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
                <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control"
                               placeholder="Ej: Hamburguesa" value="{{ old('nombre') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required>{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio <span class="text-danger">*</span></label>
                        <input type="number" name="precio" id="precio" class="form-control"
                               step="0.01" min="0" value="{{ old('precio') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="imagen" class="form-label">Subir Imagen (opcional)</label>
                        <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label for="imagen_url" class="form-label">O ingresar URL de la Imagen (opcional)</label>
                        <input type="url" name="imagen_url" id="imagen_url" class="form-control"
                               placeholder="Ej: https://ejemplo.com/imagen.jpg" value="{{ old('imagen_url') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Previsualización de la Imagen</label>
                        <div class="text-center">
                            <img id="imagen-preview" src="{{ old('imagen_url') ?: 'https://cdn-icons-png.flaticon.com/512/10446/10446694.png' }}"
                                 alt="Previsualización" class="img-thumbnail" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="categoria_ids" class="form-label">Categorías <span class="text-danger">*</span></label>
                        <select name="categoria_ids[]" id="categoria_ids" class="form-control select2" multiple required>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ in_array($categoria->id, old('categoria_ids', [])) ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ingrediente_ids" class="form-label">Ingredientes (opcional)</label>
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
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-pill">Volver</a>
                        <button type="submit" class="btn btn-primary rounded-pill">Guardar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoriaSelect = $('#categoria_ids').select2({
                placeholder: 'Seleccione una o más categorías',
                allowClear: true,
                width: '100%'
            });

            const ingredienteSelect = $('#ingrediente_ids').select2({
                placeholder: 'Seleccione uno o más ingredientes',
                allowClear: true,
                width: '100%',
                tags: false
            });

            const ingredientesTable = $('#ingredientes-table tbody');

            // Obtener los ingredientes obligatorios desde old() o vacío
            const oldIngredientesObligatorios = @json(collect(old('ingrediente_obligatorio', []))->keys()->toArray());

            function updateIngredientesTable() {
                const selectedValues = ingredienteSelect.val() || [];
                ingredientesTable.empty();

                if (selectedValues.length === 0) {
                    ingredientesTable.append('<tr><td colspan="3" class="text-center text-muted">No hay ingredientes seleccionados</td></tr>');
                    return;
                }

                selectedValues.forEach(function (id) {
                    if (!/^\d+$/.test(id)) return;

                    const option = ingredienteSelect.find(`option[value="${id}"]`);
                    const nombre = option.length ? option.text() : 'Desconocido';
                    const isChecked = oldIngredientesObligatorios.includes(id.toString()) ? 'checked' : '';

                    ingredientesTable.append(`
                        <tr data-id="${id}">
                            <td>${nombre}</td>
                            <td><input type="checkbox" name="ingrediente_obligatorio[${id}]" value="1" ${isChecked}></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-ingrediente">Eliminar</button></td>
                        </tr>
                    `);
                });
            }

            ingredienteSelect.on('change', function () {
                updateIngredientesTable();
            });

            ingredientesTable.on('click', '.remove-ingrediente', function () {
                const row = $(this).closest('tr');
                const id = row.data('id');
                ingredienteSelect.find(`option[value="${id}"]`).prop('selected', false);
                ingredienteSelect.trigger('change');
            });

            updateIngredientesTable();

            const imagenInput = document.getElementById('imagen');
            const imagenUrlInput = document.getElementById('imagen_url');
            const imagenPreview = document.getElementById('imagen-preview');

            imagenInput.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagenPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(e.target.files[0]);
                } else {
                    imagenPreview.src = imagenUrlInput.value || 'https://cdn-icons-png.flaticon.com/512/10446/10446694.png';
                }
            });

            imagenUrlInput.addEventListener('input', function () {
                if (!imagenInput.files || imagenInput.files.length === 0) {
                    const url = imagenUrlInput.value.trim();
                    imagenPreview.src = url || 'https://cdn-icons-png.flaticon.com/512/10446/10446694.png';
                }
            });

            if (window.productoDuplicado) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nombre duplicado',
                    text: `Ya existe un producto con el nombre "${window.productoDuplicado}".`,
                    confirmButtonColor: '#dc3545',
                    timer: 3000,
                    timerProgressBar: true,
                });
            }

            if (window.productoCreado) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Producto creado!',
                    text: `El producto "${window.productoCreado}" se ha creado exitosamente.`,
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        });

        @if(session('producto_duplicado'))
            window.productoDuplicado = {{ json_encode(session('producto_duplicado')) }};
        @endif
        @if(session('producto_creado'))
            window.productoCreado = {{ json_encode(session('producto_creado')) }};
        @endif
    </script>
@endsection