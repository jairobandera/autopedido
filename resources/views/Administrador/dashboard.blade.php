@extends('layouts.app-administrador')

@section('title', 'Panel de Administrador')

@section('content')
    <div class="container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h2 class="fw-bold">Bienvenido al Panel de Administración</h2>
            <p class="text-muted">Gestiona productos, categorías, promociones, usuarios y más desde un solo lugar.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Productos -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow h-100 hover-scale rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                    <img src="https://cdn-icons-png.flaticon.com/512/776/776480.png" class="card-img-top p-4" alt="Productos">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">Productos</h5>
                        <p class="text-muted mb-3">Administra el catálogo de productos de tu restaurante.</p>
                        <a href="{{ route('productos.index') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-basket me-1"></i> Gestionar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Categorías -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow h-100 hover-scale rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                    <img src="https://cdn-icons-png.flaticon.com/512/11244/11244162.png" class="card-img-top p-4" alt="Categorías">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">Categorías</h5>
                        <p class="text-muted mb-3">Organiza tus productos por categorías.</p>
                        <a href="{{ route('categorias.index') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-tags me-1"></i> Gestionar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ingredientes -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow h-100 hover-scale rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                    <img src="https://cdn-icons-png.flaticon.com/512/2884/2884593.png" class="card-img-top p-4" alt="Ingredientes">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">Ingredientes</h5>
                        <p class="text-muted mb-3">Controla los ingredientes de tus platos.</p>
                        <a href="{{ route('ingredientes.index') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-egg me-1"></i> Gestionar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Usuarios -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow h-100 hover-scale rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                    <img src="https://cdn-icons-png.flaticon.com/512/681/681392.png" class="card-img-top p-4" alt="Usuarios">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">Usuarios</h5>
                        <p class="text-muted mb-3">Gestiona los usuarios del sistema.</p>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-people me-1"></i> Gestionar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Promociones -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow h-100 hover-scale rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                    <img src="https://cdn-icons-png.flaticon.com/512/2620/2620119.png" class="card-img-top p-4" alt="Promociones">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">Promociones</h5>
                        <p class="text-muted mb-3">Crea y edita promociones para tus clientes.</p>
                        <a href="{{ route('promociones.index') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-percent me-1"></i> Gestionar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Reglas de Puntos -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow h-100 hover-scale rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
                    <img src="https://cdn-icons-png.flaticon.com/512/1077/1077012.png" class="card-img-top p-4" alt="Reglas de Puntos">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">Reglas de Puntos</h5>
                        <p class="text-muted mb-3">Configura el sistema de puntos para clientes.</p>
                        <a href="{{ route('reglas-puntos.index') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-star me-1"></i> Gestionar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Gráficas -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow h-100 hover-scale rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
                    <img src="https://cdn-icons-png.flaticon.com/512/8947/8947571.png" class="card-img-top p-4" alt="Gráficas">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">Gráficas</h5>
                        <p class="text-muted mb-3">Visualiza estadísticas de tu negocio.</p>
                        <a href="{{ route('estadisticas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-bar-chart me-1"></i> Ver estadísticas
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-title {
            font-size: 1.25rem;
            color: #212529;
        }

        .card-img-top {
            max-height: 120px;
            object-fit: contain;
            margin: 0 auto;
        }

        .btn-primary {
            background-color: #ff5722;
            border: none;
        }

        .btn-primary:hover {
            background-color: #e64a19;
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }

        @media (max-width: 768px) {
            .card-img-top {
                max-height: 100px;
            }

            .card-title {
                font-size: 1.1rem;
            }
        }
    </style>
@endpush