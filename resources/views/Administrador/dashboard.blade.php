@extends('layouts.app-administrador')

@section('title', 'Panel de Administrador')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10 text-center mb-4">
            <h2>Bienvenido al Panel de Administración</h2>
        </div>

        <div class="row justify-content-center">
            {{-- Categorías --}}
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="https://cdn-icons-png.flaticon.com/512/2917/2917242.png" class="card-img-top p-4"
                        alt="Categorías">
                    <div class="card-body text-center">
                        <h5 class="card-title">Categorías</h5>
                        <button class="btn btn-primary">Gestionar</button>
                    </div>
                </div>
            </div>
            {{-- Productos --}}
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="https://cdn-icons-png.flaticon.com/512/1046/1046784.png" class="card-img-top p-4"
                        alt="Productos">
                    <div class="card-body text-center">
                        <h5 class="card-title">Productos</h5>
                        <button class="btn btn-primary" disabled>Gestionar</button>
                    </div>
                </div>
            </div>

            {{-- Usuarios --}}
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="https://cdn-icons-png.flaticon.com/512/747/747376.png" class="card-img-top p-4"
                        alt="Usuarios">
                    <div class="card-body text-center">
                        <h5 class="card-title">Usuarios</h5>
                        <button class="btn btn-primary" disabled>Gestionar</button>
                    </div>
                </div>
            </div>

            {{-- Gráficas --}}
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="https://cdn-icons-png.flaticon.com/512/1828/1828884.png" class="card-img-top p-4"
                        alt="Gráficas">
                    <div class="card-body text-center">
                        <h5 class="card-title">Gráficas</h5>
                        <button class="btn btn-primary" disabled>Ver estadísticas</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection