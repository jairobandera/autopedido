@extends('layouts.app-administrador')

@section('title', 'Productos Deshabilitados')

@section('content')
    <div class="text-center mb-4">
        <h2>Productos Deshabilitados</h2>
    </div>

    <a href="{{ route('productos.index') }}" class="btn btn-primary mb-3">← Volver al listado</a>

    <div class="table-responsive">
        <table class="table table-hover text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Imagen</th>
                    <th>Categorías</th>
                    <th>Ingredientes</th>
                    <th>Acción</th>
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
                            <form id="form-habilitar-{{ $producto->id }}"
                                  action="{{ route('productos.habilitar', $producto->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="btn btn-success btn-sm"
                                        onclick="confirmarHabilitar({{ $producto->id }}, '{{ $producto->nombre }}')">
                                    Habilitar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No hay productos deshabilitados.</td>
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

    @if(session('error_habilitar'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ya existe un producto activo',
                    text: 'No se puede habilitar "{{ session('error_habilitar') }}" porque ya existe activo.',
                    confirmButtonColor: '#dc3545',
                });
            });
        </script>
    @endif

    <script>
        function confirmarHabilitar(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `El producto "${nombre}" será habilitado.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, habilitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-habilitar-' + id).submit();
                }
            });
        }
    </script>
@endsection