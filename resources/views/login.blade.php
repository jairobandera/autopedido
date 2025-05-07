@extends('layouts.app-login')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="logo-section">
        <img src="{{ asset('favicon.png') }}" alt="Mi Logo" style="max-width: 400px;">
    </div>
    <div class="form-section">
        {{-- Mensaje de estado de sesión --}}
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <h4 class="text-center mb-4">Iniciar Sesión</h4>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            {{-- Campo de nombre de usuario --}}
            <div class="mb-3">
                <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario"
                       class="form-control @error('nombre_usuario') is-invalid @enderror"
                       value="{{ old('nombre_usuario') }}" required autofocus>
                @error('nombre_usuario')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Campo de contraseña --}}
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Botón de enviar --}}
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    Iniciar sesión
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session("error") }}',
                    confirmButtonColor: '#dc3545',
                });
            });
        </script>
    @endif
@endsection