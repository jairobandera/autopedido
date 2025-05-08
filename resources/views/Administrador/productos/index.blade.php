@extends('layouts.app-administrador')

@section('title', 'Gestión de Productos')

@section('content')
    <div class="text-center mb-4">
        <h2>Gestión de Productos</h2>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <div class="input-group w-50">
            <form action="{{ route('productos.index') }}" method="GET" class="d-flex w-100">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar producto..."
                       value="{{ request('buscar') }}">
                <button type="submit" class="btn btn-outline-primary ms-2">Buscar</button>
            </form>
        </div>
        <div>
            <a href="{{ route('productos.create') }}" class="btn btn-success">+ Nuevo Producto</a>
            <a href="{{ route('productos.deshabilitadas') }}" class="btn btn-outline-secondary ms-2">Ver deshabilitados</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Imagen</th>
                    <th>Categorías</th>
                    <th>Ingredientes</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    <tr>
                        <td>{{ $producto->id }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>${{ number_format($producto->precio, 2) }}</td>
                        <td>
                            @if($producto->imagen)
                                <img src="{{ $producto->imagen }}" alt="{{ $producto->nombre }}"
                                     class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                            @else
                                Sin imagen
                            @endif
                        </td>
                        <td>{{ $producto->categorias->pluck('nombre')->join(', ') ?: 'Sin categorías' }}</td>
                        <td>{{ $producto->ingredientes->pluck('nombre')->join(', ') ?: 'Sin ingredientes' }}</td>
                        <td>
                            <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form id="form-deshabilitar-{{ $producto->id }}"
                                  action="{{ route('productos.deshabilitar', $producto->id) }}" method="POST"
                                  style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmarDeshabilitar({{ $producto->id }}, '{{ $producto->nombre }}')">
                                    Deshabilitar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No hay productos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $productos->links() }}
        </div>
    </div>
@endsection

@section('scripts')
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

    @if(session('producto_editado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Producto actualizado!',
                    text: 'El producto "{{ session('producto_editado') }}" fue editado correctamente.',
                    confirmButtonColor: '#0d6efd',
                });
            });
        </script>
    @endif

    @if(session('producto_deshabilitado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Producto deshabilitado',
                    text: 'El producto "{{ session('producto_deshabilitado') }}" fue deshabilitado correctamente.',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif

    @if(session('producto_habilitado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Producto habilitado!',
                    text: 'El producto "{{ session('producto_habilitado') }}" fue habilitado correctamente.',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif

    <script>
        function confirmarDeshabilitar(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `El producto "${nombre}" será deshabilitado.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, deshabilitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-deshabilitar-' + id).submit();
                }
            });
        }
    </script>
@endsection