<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Login') - Autopedido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap y SweetAlert -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: rgb(180, 180, 180);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: rgb(235, 235, 235);
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            min-width: 1200px;
            min-height: 600px;
            display: flex;
            flex-direction: row;
        }
        .logo-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(233, 233, 233, 0.1);
            border-top-left-radius: 15px;
            border-bottom-left-radius: 15px;
            padding: 20px;
        }
        .form-section {
            flex: 1;
            padding: 40px;
            background-color: #fff;
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-control {
            border-radius: 8px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
            transition: transform 0.2s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

    <main class="login-container">
        @yield('content')
    </main>

    @yield('scripts')

</body>

</html>