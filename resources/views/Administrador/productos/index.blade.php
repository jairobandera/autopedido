@extends('layouts.app-administrador')

@section('title', 'Gestión de Productos')

@section('content')
<div class="container">
    <div class="text-center mb-5 animate__animated animate__fadeIn">
        <h2 class="fw-bold">Gestión de Productos</h2>
        <p class="text-muted">Administra el catálogo de productos de tu restaurante de forma eficiente.</p>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="input-group w-100 w-md-50">
            <form action="{{ route('productos.index') }}" method="GET" class="d-flex w-100">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar producto..."
                    value="{{ request('buscar') }}" aria-label="Buscar producto">
                <button type="submit" class="btn btn-primary ms-2"><i class="bi bi-search me-1"></i> Buscar</button>
            </form>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('productos.create') }}" class="btn btn-success rounded-pill px-4">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Producto
            </a>
            <a href="{{ route('productos.deshabilitadas') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-eye-slash me-1"></i> Ver deshabilitados
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle text-center rounded-3 overflow-hidden shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categorías</th>
                    <th>Ingredientes</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                <tr class="align-middle animate__animated" style="animation-delay: {{ ($loop->index * 0.1) }}s;">
                    <td>{{ $producto->id }}</td>
                    <td>
                        @if($producto->imagen)
                        <img src="{{ $producto->imagen }}" alt="{{ $producto->nombre }}"
                            class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                        <span class="text-muted">Sin imagen</span>
                        @endif
                    </td>
                    <td>{{ $producto->nombre }}</td>
                    <td>${{ number_format($producto->precio, 2) }}</td>
                    <td>{{ $producto->categorias->pluck('nombre')->join(', ') ?: 'Sin categorías' }}</td>
                    <td>{{ $producto->ingredientes->pluck('nombre')->join(', ') ?: 'Sin ingredientes' }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-sm btn-warning rounded-pill">
                                <i class="bi bi-pencil me-1"></i> Editar
                            </a>
                            <form id="form-deshabilitar-{{ $producto->id }}"
                                action="{{ route('productos.deshabilitar', $producto->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="btn btn-sm btn-danger rounded-pill"
                                    onclick="confirmarDeshabilitar({{ $producto->id }}, '{{ $producto->nombre }}')">
                                    <i class="bi bi-slash-circle me-1"></i> Deshabilitar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-muted">No hay productos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-4">
            {{ $productos->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if(session('producto_creado'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: '¡Producto creado!',
            text: 'El producto "{{ session('
            producto_creado ') }}" se ha creado exitosamente.',
            confirmButtonColor: '#198754',
            timer: 3000,
            timerProgressBar: true,
        });
    });
</script>
@endif

@if(session('producto_editado'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: '¡Producto actualizado!',
            text: 'El producto "{{ session('
            producto_editado ') }}" fue editado correctamente.',
            confirmButtonColor: '#0d6efd',
            timer: 3000,
            timerProgressBar: true,
        });
    });
</script>
@endif

@if(session('producto_deshabilitado'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Producto deshabilitado',
            text: 'El producto "{{ session('
            producto_deshabilitado ') }}" fue deshabilitado correctamente.',
            confirmButtonColor: '#dc3545',
            timer: 3000,
            timerProgressBar: true,
        });
    });
</script>
@endif

@if(session('producto_habilitado'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: '¡Producto habilitado!',
            text: 'El producto "{{ session('
            producto_habilitado ') }}" fue habilitado correctamente.',
            confirmButtonColor: '#198754',
            timer: 3000,
            timerProgressBar: true,
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