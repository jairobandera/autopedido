<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Caja') - Eatsy</title>
    <link rel="shortcut icon" href="{{ asset('images/icono.ico') }}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.baseShowUrl = "{{ url('/caja/pedidos') }}";  // para el GET
        window.csrfToken = "{{ csrf_token() }}";
    </script>
    @vite('resources/js/cajaWebSocket/dashboard.js')

    <!-- Bootstrap + SweetAlert -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        html,
        body {
            height: 100%;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
        }

        main.container {
            flex: 1;
            padding: 20px 0;
        }

        footer {
            background-color: #343a40;
            color: #fff;
            padding: 1rem 0;
            text-align: center;
            font-size: 14px;
        }

        .navbar {
            background-color: #ff5722 !important;
        }

        .navbar-brand {
            font-weight: 600;
            color: #fff !important;
        }

        .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #f8f9fa !important;
        }

        .btn-outline-danger {
            border-color: #fff;
            color: #fff;
        }

        .btn-outline-danger:hover {
            background-color: #fff;
            color: #ff5722;
        }
    </style>
</head>

<body>
    <!-- Navbar Caja -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Eatsy | Caja <i class="bi bi-cash-coin"></i></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="/caja/dashboard" class="nav-link"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a></li>
                    <li class="nav-item"><a href="{{ route('caja.pedidos.entregados') }}" class="nav-link"><i class="bi bi-check-circle me-1"></i> Pedidos Entregados</a></li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <p class="mb-0">© {{ date('Y') }} Eatsy - Panel de Caja</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>