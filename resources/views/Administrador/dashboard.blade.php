@extends('layouts.app-administrador')

@section('title', 'Panel de Administrador')

@section('content')
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Bienvenido al Panel de Administración</h2>
            <p class="text-muted">Gestiona todo el contenido de tu plataforma desde aquí</p>
        </div>

        <div class="row g-4 justify-content-center">
            {{-- Productos --}}
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow h-100 hover-scale rounded-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/776/776480.png" class="card-img-top p-4"
                        alt="Productos">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">Productos</h5>
                        <a href="{{ route('productos.index') }}"
                            class="btn btn-outline-success rounded-pill px-4">Gestionar</a>
                    </div>
                </div>
            </div>

            {{-- Categorías --}}
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow h-100 hover-scale rounded-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/11244/11244162.png" class="card-img-top p-4"
                        alt="Categorías">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">Categorías</h5>
                        <a href="{{ route('categorias.index') }}"
                            class="btn btn-outline-primary rounded-pill px-4">Gestionar</a>
                    </div>
                </div>
            </div>

            {{-- Ingredientes --}}
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow h-100 hover-scale rounded-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/2884/2884593.png" class="card-img-top p-4"
                        alt="Ingredientes">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">Ingredientes</h5>
                        <a href="{{ route('ingredientes.index') }}"
                            class="btn btn-outline-warning rounded-pill px-4">Gestionar</a>
                    </div>
                </div>
            </div>

            {{-- Usuarios --}}
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow h-100 hover-scale rounded-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/681/681392.png" class="card-img-top p-4"
                        alt="Usuarios">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">Usuarios</h5>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-info rounded-pill px-4">Gestionar</a>
                    </div>
                </div>
            </div>

            {{-- Promociones --}}
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow h-100 hover-scale rounded-4">
                <img src="https://tse4.mm.bing.net/th?id=OIP.lLzadlgvfORpqjxs5znF2gHaHa&pid=Api" class="card-img-top p-4" alt="Promociones">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">Promociones</h5>
                        <a href="{{ route('promociones.index') }}"
                            class="btn btn-outline-info rounded-pill px-4">Gestionar</a>
                    </div>
                </div>
            </div>

            {{-- Estadísticas --}}
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow h-100 hover-scale rounded-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/8947/8947571.png" class="card-img-top p-4"
                        alt="Estadísticas">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">Estadísticas</h5>
                        <a href="{{ route('estadisticas.index') }}"
                           class="btn btn-outline-secondary rounded-pill px-4">Ver estadísticas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.03);
        }

        .card-title {
            font-size: 1.1rem;
        }
    </style>
@endpush