@extends('layouts.app-administrador')

@section('title', 'Categorías deshabilitadas')

@section('content')
<div class="text-center mb-4">
    <h2>Categorías Deshabilitadas</h2>
</div>

<a href="{{ route('categorias.index') }}" class="btn btn-primary mb-3">← Volver al listado</a>

<div class="table-responsive">
    <table class="table table-hover text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categorias as $categoria)
                <tr>
                    <td>{{ $categoria->id }}</td>
                    <td>{{ $categoria->nombre }}</td>
                    <td>
                        <form action="{{ route('categorias.habilitar', $categoria->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button class="btn btn-success btn-sm">Habilitar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No hay categorías deshabilitadas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
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
            });
        });
    </script>
@endif

@endsection
