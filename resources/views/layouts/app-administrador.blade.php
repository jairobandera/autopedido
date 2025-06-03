<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administrador') - Eatsy</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <style>
        html, body {
            height: 100%;
        }
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
        }
        main.container {
            flex: 1;
            margin-bottom: 2rem;
        }
        .card img {
            max-height: 120px;
            object-fit: contain;
        }
        footer {
            background-color: #343a40;
            color: #fff;
            padding: 1rem 0;
            text-align: center;
        }
        .charts-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
</head>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Eatsy | Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="/administrador/dashboard" class="nav-link">Inicio</a></li>
                    <li class="nav-item"><a href="{{ route('productos.index') }}" class="nav-link">Productos</a></li>
                    <li class="nav-item"><a href="{{ route('categorias.index') }}" class="nav-link">Categorías</a></li>
                    <li class="nav-item"><a href="{{ route('promociones.index') }}" class="nav-link">Promociones</a></li>
                    <li class="nav-item"><a href="{{ route('ingredientes.index') }}" class="nav-link">Ingredientes</a></li>
                    <li class="nav-item"><a href="{{ route('usuarios.index') }}" class="nav-link">Usuarios</a></li>
                    <li class="nav-item"><a href="{{ route(name: 'estadisticas.index') }}" class="nav-link">Gráficos</a></li>
                    <ul class="navbar-nav ms-3">
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">Cerrar Sesión</button>
                            </form>
                        </li>
                    </ul>
                </ul>
            </div>
        </div>
    </nav>

       <!-- Contenido -->
    <main class="container">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p class="mb-0">© {{ date('Y') }} Eatsy - Panel de Administración</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <!-- Scripts apilados -->
    @stack('scripts')
</body>
</html>