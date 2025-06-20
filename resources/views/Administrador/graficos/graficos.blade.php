@extends('layouts.app-administrador')

@section('title', 'Gráficos de Estadísticas')

@section('content')
<div class="container">
    <div class="charts-container">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h1 class="fw-bold">Panel de Gráficos</h1>
            <p class="text-muted">Visualiza las métricas de tu negocio</p>
        </div>

        <!-- Formulario de filtro por fechas -->
        <div class="mb-4">
            <form action="{{ route('estadisticas.index') }}" method="GET" class="row g-3 justify-content-center align-items-end">
                <div class="col-md-4">
                    <label for="start_date" class="form-label fw-bold">Fecha Inicial <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label fw-bold">Fecha Final <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary rounded-pill w-100 py-2">
                        <i class="bi bi-filter me-1"></i> Filtrar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('estadisticas.export-pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="btn btn-success rounded-pill w-100 py-2 d-flex justify-content-center align-items-center">
                        <i class="bi bi-file-pdf me-1"></i> Exportar
                    </a>
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
            <div class="col-md-12">
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
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar y renderizar cada gráfico
        const charts = [{
                id: 'ventasDiariasChart',
                data: @json($ventasDiariasChart)
            },
            {
                id: 'masVendidosChart',
                data: @json($masVendidosChart)
            },
            {
                id: 'ventasPorCategoriaChart',
                data: @json($ventasPorCategoriaChart)
            },
            {
                id: 'pedidosPorEstadoChart',
                data: @json($pedidosPorEstadoChart)
            },
            {
                id: 'ingresosPorHoraChart',
                data: @json($ingresosPorHoraChart)
            }
        ];

        charts.forEach(chart => {
            const ctx = document.getElementById(chart.id)?.getContext('2d');
            if (ctx && chart.data) {
                new Chart(ctx, chart.data);
            }
        });
    });
</script>
@endpush