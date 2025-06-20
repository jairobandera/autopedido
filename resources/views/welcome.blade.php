<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Eatsy: Solución de autogestión de pedidos para restaurantes con pantallas táctiles y gestión en tiempo real.">
    <meta name="keywords" content="autogestión, pedidos, restaurantes, Eatsy, tecnología">
    <meta name="author" content="Eatsy">
    <title>Eatsy - Autogestión de Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            transition: background-color 0.3s ease;
        }

        .navbar-brand img {
            height: 60px;
        }

        .navbar-nav .nav-link {
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

        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/assets/banner.png') center/cover no-repeat;
            min-height: 100vh;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 5rem 2rem;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.25rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .btn-primary {
            background-color: #ff5722;
            border: none;
            padding: 0.75rem 2rem;
        }

        .btn-primary:hover {
            background-color: #e64a19;
        }

        .feature-img {
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
            max-width: 100%;
        }

        .feature-img:hover {
            transform: scale(1.03);
        }

        .contact-icons a {
            font-size: 2rem;
            margin: 0 1rem;
            color: #212529;
            transition: color 0.3s;
        }

        .contact-icons a:hover {
            color: #ff5722;
        }

        footer {
            background-color: #212529;
        }

        .testimonial-card {
            background-color: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
        }

        .testimonial-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate__animated.animate__fadeIn {
            animation: fadeIn 1s ease-in-out;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="images/logo.png" alt="Eatsy" class="img-fluid">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link fs-5 mx-2" href="#features">Características</a></li>
                    <li class="nav-item"><a class="nav-link fs-5 mx-2" href="#screenshots">Pantallas</a></li>
                    <li class="nav-item"><a class="nav-link fs-5 mx-2" href="#testimonials">Testimonios</a></li>
                    <li class="nav-item"><a class="nav-link fs-5 mx-2" href="#contact">Contacto</a></li>
                    <li class="nav-item"><a class="nav-link fs-5 mx-2" href="/login">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="animate__animated animate__fadeIn">
            <h1 class="fw-bold">Autogestión de Pedidos para Restaurantes</h1>
            <p class="mt-3">Permite a tus clientes hacer su pedido en pantalla táctil y agiliza el flujo de caja y cocina en tiempo real.</p>
            <div class="mt-4">
                <a href="#features" class="btn btn-primary btn-lg me-2">Conoce Más</a>
                <a href="/demo" class="btn btn-outline-light btn-lg">Solicita una Demo</a>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center mb-5">¿Qué ofrece Eatsy?</h2>
            <div class="row g-4">
                <div class="col-md-4 text-center animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                    <img src="images/assets/cliente.jpg" alt="Cliente" class="feature-img mb-3">
                    <h5>Toma de Pedido</h5>
                    <p>Interfaz intuitiva para que el cliente realice su pedido sin asistencia.</p>
                </div>
                <div class="col-md-4 text-center animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                    <img src="images/assets/cajero-dashboard.jpg" alt="Cajero" class="feature-img mb-3">
                    <h5>Gestión de Caja</h5>
                    <p>Visualiza y cobra pedidos en tiempo real, controla estado de pago y envía a cocina.</p>
                </div>
                <div class="col-md-4 text-center animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                    <img src="images/assets/cocina.jpg" alt="Cocina" class="feature-img mb-3">
                    <h5>Panel de Cocina</h5>
                    <p>Detalle de ingredientes y estado de preparación, notificando al cajero al finalizar.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Lo que dicen nuestros clientes</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                        <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="Cliente 1" class="testimonial-img">
                        <h5>María González</h5>
                        <p>"Eatsy ha transformado la forma en que manejamos los pedidos en nuestro restaurante. ¡Los clientes adoran la rapidez y la facilidad!"</p>
                        <small>Propietaria, La Cocina de María</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                        <img src="https://randomuser.me/api/portraits/men/8.jpg" alt="Cliente 2" class="testimonial-img">
                        <h5>Juan Pérez</h5>
                        <p>"La integración con la cocina es impecable. Ahora nuestro equipo trabaja más sincronizado y los pedidos salen más rápido."</p>
                        <small>Gerente, Sabor Urbano</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                        <img src="https://randomuser.me/api/portraits/women/3.jpg" alt="Cliente 3" class="testimonial-img">
                        <h5>Ana López</h5>
                        <p>"El sistema de autogestión ha reducido los errores en los pedidos y ha mejorado la experiencia de nuestros clientes."</p>
                        <small>Administradora, Comida Rápida Express</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Screenshots Gallery -->
    <section id="screenshots" class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5">Galería de Pantallas</h2>
            <div class="row g-4">
                <div class="col-md-6 text-center">
                    <img src="images/assets/llamado al cliente.jpg" alt="Llamado Cliente"
                        class="feature-img d-block mx-auto mb-3" style="width: 100%; max-width: 600px;">
                    <h5>Pantalla de Llamado al Cliente</h5>
                    <p>El cliente podrá ver en tiempo real cuándo su pedido está listo para retirar.</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="images/assets/comprobanteCompra.jpg" alt="Comprobante de Pedido"
                        class="feature-img d-block mx-auto mb-3" style="width: 75%; max-width: 300px;">
                    <h5>Comprobante de Pedido</h5>
                    <p>El cajero recibirá un comprobante con código de barras para escanear y recuperar rápidamente el
                        pedido.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="mb-4">Contáctanos</h2>
            <p>Envíanos un mensaje o síguenos en nuestras redes:</p>
            <div class="contact-icons mt-3 mb-4">
                <a href="https://instagram.com/tu_instagram" target="_blank" class="mx-3"><i class="bi bi-instagram fs-2"></i></a>
                <a href="https://wa.me/549XXXXXXXXXX" target="_blank" class="mx-3"><i class="bi bi-whatsapp fs-2"></i></a>
                <a href="mailto:contacto@eatsy.com" class="mx-3"><i class="bi bi-envelope fs-2"></i></a>
            </div>
            <form class="row g-3 justify-content-center">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Nombre" required>
                </div>
                <div class="col-md-6">
                    <input type="email" class="form-control" placeholder="Correo Electrónico" required>
                </div>
                <div class="col-12">
                    <textarea class="form-control" rows="4" placeholder="Tu mensaje" required></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center text-md-start">
                    <img src="images/logo.png" alt="Eatsy" height="40" class="mb-3">
                    <p>Autogestión de pedidos para restaurantes modernos.</p>
                </div>
                <div class="col-md-4 text-center">
                    <h5>Enlaces</h5>
                    <ul class="list-unstyled">
                        <li><a href="/privacy" class="text-light text-decoration-none">Política de Privacidad</a></li>
                        <li><a href="/terms" class="text-light text-decoration-none">Términos de Uso</a></li>
                        <li><a href="#contact" class="text-light text-decoration-none">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <h5>Síguenos</h5>
                    <div class="contact-icons">
                        <a href="https://instagram.com/tu_instagram" target="_blank"><i class="bi bi-instagram me-2"></i></a>
                        <a href="https://wa.me/549XXXXXXXXXX" target="_blank"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-light">
            <p class="text-center mb-0"><small>© 2025 Eatsy. Todos los derechos reservados.</small></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>