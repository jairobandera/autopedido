<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eatsy - Autogestión de Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hero {
            background: url('images/assets/banner.png') center/cover no-repeat;
            min-height: 100vh;
            position: relative;
        }

        .hero-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            position: absolute;
            inset: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            color: #fff;
        }

        .feature-img {
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .contact-icons a {
            font-size: 2rem;
            margin: 0 1rem;
            color: #333;
        }

        .contact-icons a:hover {
            color: #007bff;
        }
        nav{ height: 5rem; }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="images/logo.png" alt="Eatsy" height="100">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link fs-5 mx-2" href="#features">Características</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-5 mx-2" href="#screenshots">Pantallas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-5 mx-2" href="#contact">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-5 mx-2" href="/login">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero d-flex align-items-center">
        <div class="hero-overlay"></div>
        <div class="container hero-content text-center py-5">
            <h1 class="display-4 fw-bold">Autogestión de Pedidos para Restaurantes</h1>
            <p class="lead">Permite a tus clientes hacer su pedido en pantalla táctil y agiliza el flujo de caja y
                cocina en tiempo real.</p>
            <a href="#features" class="btn btn-primary btn-lg mt-3">Conoce Más</a>
        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">¿Qué ofrece Eatsy?</h2>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <img src="images/assets/cliente.jpg" alt="Cliente" class="feature-img mb-3" width="100%">
                    <h5>Toma de Pedido</h5>
                    <p>Interfaz intuitiva para que el cliente realice su pedido sin asistencia.</p>
                </div>
                <div class="col-md-4 text-center">
                    <img src="images/assets/cajero-dashboard.jpg" alt="Cajero" class="feature-img mb-3" width="100%">
                    <h5>Gestión de Caja</h5>
                    <p>Visualiza y cobra pedidos en tiempo real, controla estado de pago y envía a cocina.</p>
                </div>
                <div class="col-md-4 text-center">
                    <img src="images/assets/cocina.jpg" alt="Cocina" class="feature-img mb-3" width="100%">
                    <h5>Panel de Cocina</h5>
                    <p>Detalle de ingredientes y estado de preparación, notificando al cajero al finalizar.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Screenshots Gallery -->
    <section id="screenshots" class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-4">Galería de Pantallas</h2>
            <div class="row g-4">

                <!-- Llamado al cliente -->
                <div class="col-md-6 text-center">
                    <img src="images/assets/llamado al cliente.jpg" alt="Llamado Cliente"
                        class="feature-img d-block mx-auto mb-3" style="width: 100%; max-width: 600px;">
                    <h5>Pantalla de Llamado al Cliente</h5>
                    <p>El cliente podrá ver en tiempo real cuándo su pedido está listo para retirar.</p>
                </div>

                <!-- Comprobante de pedido -->
                <div class="col-md-6 text-center">
                    <img src="images/assets/comprobanteCompra.jpg" alt="Comprobante de Pedido"
                        class="feature-img d-block mx-auto mb-3" style="width: 75%; max-width: 300px;">
                    <h5>Comprobante de Pedido</h5>
                    <p>El cajero recibirá un comprobante con código de barras para escanear y recuperar rápidamente el
                        pedido, acelerando la entrega.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container text-center">
            <h2 class="mb-4">Contáctanos</h2>
            <p>Síguenos o envíanos un mensaje directo en:</p>
            <div class="contact-icons">
                <a href="https://instagram.com/tu_instagram" target="_blank"><i class="bi bi-instagram"></i></a>
                <a href="https://wa.me/549XXXXXXXXXX" target="_blank"><i class="bi bi-whatsapp"></i></a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-3">
        <div class="container text-center">
            <small>&copy; 2025 Eatsy. Todos los derechos reservados.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
</body>

</html>