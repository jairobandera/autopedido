<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panel de administración de Eatsy para gestionar productos, categorías, promociones, usuarios y más.">
    <meta name="keywords" content="Eatsy, administración, restaurante, autogestión">
    <meta name="author" content="Eatsy">
    <title>@yield('title', 'Administrador') - Eatsy</title>
    <link rel="shortcut icon" href="{{ asset('images/icono.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e0e0e0 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main.container {
            flex: 1;
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        /* Navbar */
        .navbar {
            background-color: #212529;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-brand img {
            height: 40px;
        }

        .navbar-nav .nav-link {
            color: #fff;
            font-size: 1rem;
            margin: 0 0.5rem;
            position: relative;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #ff5722;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #ff5722;
            transition: width 0.3s ease;
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        .btn-logout {
            background-color: #dc3545;
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            color: #fff;
            transition: background-color 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #b02a37;
        }

        /* Main Content */
        .card {
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card img {
            max-height: 120px;
            object-fit: contain;
            border-radius: 0.75rem 0.75rem 0 0;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .btn-primary {
            background-color: #ff5722;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #e64a19;
        }

        .table {
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #ff5722;
            color: #fff;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .charts-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        /* Footer */
        footer {
            background-color: #212529;
            color: #fff;
            padding: 2rem 0;
            margin-top: auto;
        }

        footer a {
            color: #ff5722;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0.6; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate__animated.animate__fadeInUp {
            animation: fadeInUp 0.6s ease-in-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-nav .nav-link {
                margin: 0.5rem 0;
            }

            main.container {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 animate__animated">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/administrador/dashboard">
                <img src="{{ asset('images/logo.png') }}" alt="Eatsy Logo" class="me-2">
                Eatsy | Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a href="/administrador/dashboard" class="nav-link"><i class="bi bi-house me-1"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('productos.index') }}" class="nav-link"><i class="bi bi-basket me-1"></i> Productos</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('categorias.index') }}" class="nav-link"><i class="bi bi-tags me-1"></i> Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('promociones.index') }}" class="nav-link"><i class="bi bi-percent me-1"></i> Promociones</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ingredientes.index') }}" class="nav-link"><i class="bi bi-egg me-1"></i> Ingredientes</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('usuarios.index') }}" class="nav-link"><i class="bi bi-people me-1"></i> Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reglas-puntos.index') }}" class="nav-link"><i class="bi bi-star me-1"></i> Puntos</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route(name: 'estadisticas.index') }}" class="nav-link"><i class="bi bi-bar-chart me-1"></i> Gráficas</a>
                    </li>
                    <li class="nav-item ms-3">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="btn btn-logout"><i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container animate__animated animate__fadeInUp">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row text-center text-md-start">
                <div class="col-md-4 mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Eatsy Logo" height="40" class="mb-2">
                    <p class="mb-0">Panel de administración para Eatsy, la solución de autogestión de pedidos.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="mb-2">Enlaces</h5>
                    <ul class="list-unstyled">
                        <li><a href="/privacy">Política de Privacidad</a></li>
                        <li><a href="/terms">Términos de Uso</a></li>
                        <li><a href="/support">Soporte</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="mb-2">Contacto</h5>
                    <p class="mb-0"><i class="bi bi-envelope me-1"></i> contacto@eatsy.com</p>
                    <p class="mb-0"><i class="bi bi-whatsapp me-1"></i> +54 9 XXXX XXXXX</p>
                </div>
            </div>
            <hr class="bg-light">
            <p class="text-center mb-0">© {{ date('Y') }} Eatsy - Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 Logout Confirmation -->
    <script>
        document.getElementById('logout-form').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: '¿Estás seguro de que quieres cerrar tu sesión?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff5722',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>