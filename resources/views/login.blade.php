@extends('layouts.app-login')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="height: 75vh;">
        <div class="card p-4 shadow-lg" style="min-width: 400px;">
            <h3 class="text-center mb-4">Iniciar Sesión</h3>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="mb-3">
                    <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
            </form>
        </div>
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