<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Cliente') &mdash; Eatsy</title>
    <link rel="shortcut icon" href="{{ asset('images/icono.ico') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Variables JS -->
    <script>
        window.baseShowUrl = "{{ url('/caja/pedidos') }}";
        window.csrfToken = "{{ csrf_token() }}";
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        html,
        body {
            height: 100%;
        }

        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
        }

        main.container {
            flex: 1;
        }

        footer {
            background-color: #343a40;
            color: #fff;
            padding: 1rem 0;
            text-align: center;
        }
    </style>

    @yield('head'){{-- para estilos o scripts adicionales en

    <head> --}}
    </head>

<body>
    {{-- NAVBAR CLIENTE --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Eatsy | Cliente</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navCliente">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navCliente">
            </div>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Eatsy</p>
        </div>
    </footer>

    @vite('resources/js/clienteWebSocket/llamado.js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>