@extends('layouts.app-administrador')

@section('title', 'Categorías Deshabilitadas')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Categorías Deshabilitadas</h2>
            <p class="text-muted">Revisa y habilita categorías previamente deshabilitadas.</p>
        </div>

        <a href="{{ route('categorias.index') }}" class="btn btn-outline-primary mb-4 rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>

        <div class="table-responsive">
            <table class="table table-hover text-center align-middle rounded-3 overflow-hidden shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                        <tr class="align-middle">
                            <td>{{ $categoria->id }}</td>
                            <td>{{ $categoria->nombre }}</td>
                            <td>
                                <form action="{{ route('categorias.habilitar', $categoria->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i> Habilitar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-muted">No hay categorías deshabilitadas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @if(session('error_habilitar'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ya existe una categoría activa',
                    text: 'No se puede habilitar "{{ session('error_habilitar') }}" porque ya existe activa.',
                    confirmButtonColor: '#dc3545',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    @if(session('categoria_habilitada'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Categoría habilitada!',
                    text: 'La categoría "{{ session('categoria_habilitada') }}" fue habilitada correctamente.',
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif
@endsection