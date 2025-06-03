@extends('layouts.app-administrador')

@section('title', 'Gráficos de Estadísticas')

@section('content')
    <div class="container">
        <div class="charts-container">
            <div class="text-center mb-5">
                <h1 class="fw-bold">Panel de Gráficos</h1>
                <p class="text-muted">Visualiza las métricas de tu negocio</p>
            </div>

            <!-- Formulario de filtro por fechas -->
<div class="mb-4">
    <form action="{{ route('estadisticas.index') }}" method="GET" class="row g-3 justify-content-center align-items-end">
        <div class="col-md-4">
            <label for="start_date" class="form-label">Fecha Inicial</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}" required>
        </div>
        <div class="col-md-4">
            <label for="end_date" class="form-label">Fecha Final</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary rounded-pill w-100">Filtrar</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('estadisticas.export-pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success rounded-pill w-100">Exportar a PDF</a>
        </div>
    </form>
</div>

            <!-- Gráficos -->
            <div class="row g-4">
                <!-- Ventas Diarias -->
                <div class="col-md-6">
                    <div class="card border-0 shadow rounded-4">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">Ventas Diarias</h5>
                            <canvas id="ventasDiariasChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Productos Más Vendidos -->
                <div class="col-md-6">
                    <div class="card border-0 shadow rounded-4">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">Productos Más Vendidos</h5>
                            <canvas id="masVendidosChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Ventas por Categoría -->
                <div class="col-md-6">
                    <div class="card border-0 shadow rounded-4">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">Ventas por Categoría</h5>
                            <canvas id="ventasPorCategoriaChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Pedidos por Estado -->
                <div class="col-md-6">
                    <div class="card border-0 shadow rounded-4">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">Pedidos por Estado</h5>
                            <canvas id="pedidosPorEstadoChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Ingresos por Hora -->
                <div class="col-md-6">
                    <div class="card border-0 shadow rounded-4">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">Ingresos por Hora</h5>
                            <canvas id="ingresosPorHoraChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ventas Diarias
            const ctx1 = document.getElementById('ventasDiariasChart')?.getContext('2d');
            if (ctx1) new Chart(ctx1, @json($ventasDiariasChart));

            // Productos Más Vendidos
            const ctx2 = document.getElementById('masVendidosChart')?.getContext('2d');
            if (ctx2) new Chart(ctx2, @json($masVendidosChart));

            // Ventas por Categoría
            const ctx3 = document.getElementById('ventasPorCategoriaChart')?.getContext('2d');
            if (ctx3) new Chart(ctx3, @json($ventasPorCategoriaChart));

            // Pedidos por Estado
            const ctx4 = document.getElementById('pedidosPorEstadoChart')?.getContext('2d');
            if (ctx4) new Chart(ctx4, @json($pedidosPorEstadoChart));

            // Ingresos por Hora
            const ctx5 = document.getElementById('ingresosPorHoraChart')?.getContext('2d');
            if (ctx5) new Chart(ctx5, @json($ingresosPorHoraChart));
        });
    </script>
@endpush