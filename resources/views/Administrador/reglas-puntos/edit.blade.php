{{-- resources/views/Administrador/reglas-puntos/edit.blade.php --}}
@extends('layouts.app-administrador')

@section('title', 'Editar Tramo de Puntos #' . $reglaPunto->id)

@section('content')
    <div class="text-center mb-4">
        <h2>Editar Tramo de Puntos</h2>
    </div>

    {{-- Errores de validación --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Corrige los siguientes errores:<br><br>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="{{ route('reglas-puntos.update', $reglaPunto->id) }}" method="POST" id="form-editar-regla">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="monto_min" class="form-label">Monto Mínimo</label>
                    <input type="number" step="0.01" min="0" name="monto_min" id="monto_min"
                        class="form-control @error('monto_min') is-invalid @enderror"
                        value="{{ old('monto_min', $reglaPunto->monto_min) }}" required>
                    @error('monto_min')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="monto_max" class="form-label">Monto Máximo</label>
                    <input type="number" step="0.01" min="0" name="monto_max" id="monto_max"
                        class="form-control @error('monto_max') is-invalid @enderror"
                        value="{{ old('monto_max', $reglaPunto->monto_max) }}" required>
                    @error('monto_max')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="puntos_base" class="form-label">Puntos por Pedido en este Rango</label>
                    <input type="number" step="1" min="1" name="puntos_base" id="puntos_base"
                        class="form-control @error('puntos_base') is-invalid @enderror"
                        value="{{ old('puntos_base', $reglaPunto->puntos_base) }}" required>
                    @error('puntos_base')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nota explicativa sobre los tramos --}}
                <div class="alert alert-secondary small">
                    Ajusta los valores de este tramo. Si el total del pedido está entre
                    <strong>Monto Mínimo</strong> y <strong>Monto Máximo</strong>, se otorgarán
                    <strong>Puntos por Pedido</strong>.
                </div>

                {{-- Vista previa dinámica --}}
                <div id="preview-regla" class="alert alert-info d-none"></div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('reglas-puntos.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Tramo</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const minField = document.getElementById('monto_min');
            const maxField = document.getElementById('monto_max');
            const pbField = document.getElementById('puntos_base');
            const preview = document.getElementById('preview-regla');
            const form = document.getElementById('form-editar-regla');

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

            // Inicializar con valores actuales
            actualizarPreview();

            // Validar que min ≤ max antes de enviar
            form.addEventListener('submit', e => {
                const min = parseFloat(minField.value);
                const max = parseFloat(maxField.value);
                if (min > max) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error de validación',
                        text: 'El Monto Mínimo no puede ser mayor al Monto Máximo.',
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        });
    </script>
@endsection