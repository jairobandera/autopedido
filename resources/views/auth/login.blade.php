<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Inicia sesión en Eatsy para gestionar tus pedidos de restaurante de forma rápida y eficiente.">
    <meta name="keywords" content="Eatsy, login, autogestión, restaurante">
    <meta name="author" content="Eatsy">
    <title>Iniciar Sesión - Eatsy</title>
    <link rel="shortcut icon" href="{{ asset('images/icono.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e0e0e0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-container {
            max-width: 500px;
            width: 100%;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            padding: 2rem;
            margin: auto;
        }

        .login-container img {
            max-width: 150px;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #ff5722;
            box-shadow: 0 0 0 0.2rem rgba(255, 87, 34, 0.25);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 0.5rem 0 0 0.5rem;
        }

        .btn-primary {
            background-color: #ff5722;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 2rem;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #e64a19;
        }

        .text-error {
            color: #dc3545;
            font-size: 0.875rem;
        }

        .forgot-password {
            color: #ff5722;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .forgot-password:hover {
            text-decoration: underline;
            color: #e64a19;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate__animated.animate__fadeInUp {
            animation: fadeInUp 0.6s ease-in-out;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 1.5rem;
            }

            .login-container img {
                max-width: 120px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container animate__animated animate__fadeInUp">
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Eatsy Logo" class="w-32 mx-auto animate__animated animate__fadeIn" width="150px">
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Nombre de Usuario -->
            <div class="mb-4">
                <label for="nombre" class="form-label">{{ __('Nombre de Usuario') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input id="nombre" class="form-control" type="text" name="nombre" value="{{ old('nombre') }}"
                        required autofocus autocomplete="username" aria-describedby="nombreError">
                </div>
                <x-input-error :messages="$errors->get('nombre')" class="text-error mt-1" id="nombreError" />
            </div>

            <!-- Contraseña -->
            <div class="mb-4">
                <label for="contrasena" class="form-label">{{ __('Contraseña') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input id="contrasena" class="form-control" type="password" name="contrasena" required
                        autocomplete="current-password" aria-describedby="contrasenaError">
                </div>
                <x-input-error :messages="$errors->get('contrasena')" class="text-error mt-1" id="contrasenaError" />
            </div>

            <!-- Forgot Password and Submit Button -->
            <div class="d-flex align-items-center justify-content-between mt-4">
                @if (Route::has('password.request'))
                    <a class="forgot-password" href="{{ route('password.request') }}">
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif
                <button type="submit" class="btn btn-primary">
                    {{ __('Iniciar sesión') }}
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>