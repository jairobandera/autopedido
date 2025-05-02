<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Administrador') - Eatsy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap + SweetAlert -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
    </style>
</head>

<body>

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
                    <li class="nav-item"><a href="{{ route('categorias.index') }}" class="nav-link">Categorías</a></li>
                    <li class="nav-item"><a href="{{ route('productos.index') }}" class="nav-link">Productos</a></li>
                    <li class="nav-item"><a href="{{ route('ingredientes.index') }}" class="nav-link">Ingredientes</a></li>
                    <li class="nav-item"><a href="{{ route('usuarios.index') }}" class="nav-link">Usuarios</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Gráficas</a></li>
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

    {{-- CONTENIDO --}}
    <main class="container">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer>
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Eatsy - Panel de Administración</p>
        </div>
    </footer>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    @yield('scripts')

</body>

</html>