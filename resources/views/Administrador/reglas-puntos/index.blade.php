@extends('layouts.app-administrador')

@section('title', 'Gestión de Reglas de Puntos')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Gestión de Puntos</h2>
            <p class="text-muted">Administra las reglas de puntos para diferentes rangos de monto.</p>
        </div>

        <button class="btn btn-success mb-4 rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalCrearRegla">
            <i class="bi bi-plus-lg me-1"></i> Nueva regla
        </button>

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#198754',
                        timer: 3000,
                        timerProgressBar: true,
                    });
                });
            </script>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center rounded-3 overflow-hidden shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Rango Monto</th>
                        <th>Puntos Base</th>
                        <th>Creada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reglas as $regla)
                        <tr class="align-middle">
                            <td>{{ $regla->id }}</td>
                            <td>
                                ${{ number_format($regla->monto_min, 2) }}
                                –
                                ${{ number_format($regla->monto_max, 2) }}
                            </td>
                            <td>{{ $regla->puntos_base }}</td>
                            <td>{{ $regla->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('reglas-puntos.edit', $regla->id) }}" class="btn btn-sm btn-warning rounded-pill">
                                        <i class="bi bi-pencil me-1"></i> Editar
                                    </a>
                                    <form id="delete-form-{{ $regla->id }}" action="{{ route('reglas-puntos.destroy', $regla->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" data-id="{{ $regla->id }}" class="btn btn-sm btn-danger rounded-pill btn-eliminar-regla">
                                            <i class="bi bi-trash me-1"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">No hay tramos de puntos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="alert alert-info mt-3">
                <strong>Nota:</strong> Para montos que no entren en ningún tramo definido, se asignará por defecto 1 punto.
            </div>
        </div>

        <!-- Modal para crear nuevo tramo -->
        <div class="modal fade" id="modalCrearRegla" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="form-crear-regla" action="{{ route('reglas-puntos.store') }}" method="POST">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Nuevo Tramo de Puntos</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="monto_min" class="form-label fw-bold">Monto Mínimo <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="monto_min" id="monto_min"
                                       class="form-control @error('monto_min') is-invalid @enderror" value="{{ old('monto_min') }}"
                                       required>
                                @error('monto_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="monto_max" class="form-label fw-bold">Monto Máximo <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="monto_max" id="monto_max"
                                       class="form-control @error('monto_max') is-invalid @enderror" value="{{ old('monto_max') }}"
                                       required>
                                @error('monto_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="puntos_base" class="form-label fw-bold">Puntos por Pedido en este Rango <span class="text-danger">*</span></label>
                                <input type="number" step="1" min="1" name="puntos_base" id="puntos_base"
                                       class="form-control @error('puntos_base') is-invalid @enderror" value="{{ old('puntos_base') }}"
                                       required>
                                @error('puntos_base')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-secondary small">
                                Define aquí un tramo de montos. Si el total del pedido está entre
                                <strong>Monto Mínimo</strong> y <strong>Monto Máximo</strong>, se otorgarán
                                <strong>Puntos por Pedido</strong>.
                            </div>

                            <div id="preview-regla" class="alert alert-info d-none"></div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Guardar Tramo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@section('scripts')
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                new bootstrap.Modal(document.getElementById('modalCrearRegla')).show();
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const minField = document.getElementById('monto_min');
            const maxField = document.getElementById('monto_max');
            const pbField = document.getElementById('puntos_base');
            const preview = document.getElementById('preview-regla');
            const form = document.getElementById('form-crear-regla');

            function actualizarPreview() {
                const min = parseFloat(minField.value);
                const max = parseFloat(maxField.value);
                const pb = parseInt(pbField.value, 10);
                if (!isNaN(min) && !isNaN(max) && !isNaN(pb)) {
                    preview.textContent =
                        `Pedidos entre $${min.toFixed(2)} y $${max.toFixed(2)} generarán ${pb} punto(s).`;
                    preview.classList.remove('d-none');
                } else {
                    preview.classList.add('d-none');
                }
            }

            [minField, maxField, pbField].forEach(el =>
                el.addEventListener('input', actualizarPreview)
            );

            form.addEventListener('submit', (e) => {
                const min = parseFloat(minField.value);
                const max = parseFloat(maxField.value);
                if (!isNaN(min) && !isNaN(max) && min > max) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error de validación',
                        text: 'El Monto Mínimo no puede ser mayor al Monto Máximo.',
                        confirmButtonColor: '#dc3545'
                    });
                }
            });

            document.querySelectorAll('.btn-eliminar-regla')
                .forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.getAttribute('data-id');
                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: 'Este tramo se eliminará definitivamente.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then(result => {
                            if (result.isConfirmed) {
                                document
                                    .getElementById(`delete-form-${id}`)
                                    .submit();
                            }
                        });
                    });
                });
        });
    </script>
@endsection