@extends('layouts.app-administrador')

@section('title', 'Gestión de Ingredientes')

@section('content')
    <div class="text-center mb-4">
        <h2>Gestión de Ingredientes</h2>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <div class="input-group w-50">
            <form action="{{ route('ingredientes.index') }}" method="GET" class="d-flex w-100">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar ingrediente..."
                       value="{{ request('buscar') }}">
                <button type="submit" class="btn btn-outline-primary ms-2">Buscar</button>
            </form>
        </div>
        <div>
            <a href="{{ route('ingredientes.create') }}" class="btn btn-success">+ Nuevo Ingrediente</a>
            <a href="{{ route('ingredientes.deshabilitadas') }}" class="btn btn-outline-secondary ms-2">Ver deshabilitados</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ingredientes as $ingrediente)
                    <tr>
                        <td>{{ $ingrediente->id }}</td>
                        <td>{{ $ingrediente->nombre }}</td>
                        <td>{{ $ingrediente->descripcion }}</td>
                        <td>
                            <a href="{{ route('ingredientes.edit', $ingrediente->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form id="form-deshabilitar-{{ $ingrediente->id }}"
                                  action="{{ route('ingredientes.deshabilitar', $ingrediente->id) }}" method="POST"
                                  style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmarDeshabilitar({{ $ingrediente->id }}, '{{ $ingrediente->nombre }}')">
                                    Deshabilitar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No hay ingredientes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $ingredientes->links() }}
        </div>
    </div>
@endsection

@section('scripts')
    @if(session('ingrediente_creado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Ingrediente creado!',
                    text: 'El ingrediente "{{ session('ingrediente_creado') }}" se ha creado exitosamente.',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif

    @if(session('ingrediente_editado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Ingrediente actualizado!',
                    text: 'El ingrediente "{{ session('ingrediente_editado') }}" fue editado correctamente.',
                    confirmButtonColor: '#0d6efd',
                });
            });
        </script>
    @endif

    @if(session('ingrediente_deshabilitado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Ingrediente deshabilitado',
                    text: 'El ingrediente "{{ session('ingrediente_deshabilitado') }}" fue deshabilitado correctamente.',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif

    @if(session('ingrediente_habilitado'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Ingrediente habilitado!',
                    text: 'El ingrediente "{{ session('ingrediente_habilitado') }}" fue habilitado correctamente.',
                    confirmButtonColor: '#198754',
                });
            });
        </script>
    @endif

    <script>
        function confirmarDeshabilitar(id, nombre) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `El ingrediente "${nombre}" será deshabilitado.`,
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