@extends('layouts.app-administrador')

@section('title', 'Productos Deshabilitados')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Productos Deshabilitados</h2>
            <p class="text-muted">Revisa y habilita productos previamente deshabilitados.</p>
        </div>

        <a href="{{ route('productos.index') }}" class="btn btn-outline-primary mb-4 rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>

        <div class="table-responsive">
            <table class="table table-hover text-center align-middle rounded-3 overflow-hidden shadow-sm">
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
                        <tr class="align-middle">
                            <td>{{ $producto->id }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>${{ number_format($producto->precio, 2) }}</td>
                            <td>
                                @if($producto->imagen)
                                    <img src="{{ $producto->imagen }}" alt="{{ $producto->nombre }}"
                                         class="img-thumbnail" style="max-width: 80px; max-height: 80px; object-fit: cover;">
                                @else
                                    <span class="text-muted">Sin imagen</span>
                                @endif
                            </td>
                            <td>{{ $producto->categorias->pluck('nombre')->join(', ') ?: 'Sin categorías' }}</td>
                            <td>{{ $producto->ingredientes->pluck('nombre')->join(', ') ?: 'Sin ingredientes' }}</td>
                            <td>
                                <form id="form-habilitar-{{ $producto->id }}"
                                      action="{{ route('productos.habilitar', $producto->id) }}" method="POST"
                                      style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button" class="btn btn-success btn-sm rounded-pill"
                                            onclick="confirmarHabilitar({{ $producto->id }}, '{{ $producto->nombre }}')">
                                        <i class="bi bi-check-circle me-1"></i> Habilitar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted">No hay productos deshabilitados.</td>
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
    <script>
        window.productoHabilitado = @json(session('producto_habilitado'));
        window.errorHabilitar = @json(session('error_habilitar'));
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Éxito al habilitar producto
            if (window.productoHabilitado) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Producto habilitado!',
                    text: `El producto "${window.productoHabilitado}" fue habilitado correctamente.`,
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            }

            // Error al habilitar producto
            if (window.errorHabilitar) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ya existe un producto activo',
                    text: `No se puede habilitar "${window.errorHabilitar}" porque ya existe activo.`,
                    confirmButtonColor: '#dc3545',
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        });

        window.confirmarHabilitar = function (id, nombre) {
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
        };
    </script>
@endsection