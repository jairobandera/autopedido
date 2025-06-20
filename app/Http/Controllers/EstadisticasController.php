<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\DetallePedido;
use App\Models\Categoria;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class EstadisticasController extends Controller
{
    private function getStatisticsData($startDate, $endDate)
    {
        // Asegurar que endDate incluya todo el día
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Ventas diarias
        $ventasDiarias = Pago::selectRaw('DATE(fecha) as fecha, SUM(monto) as total')
            ->whereBetween('fecha', [$startDate, $endDate])
            ->where('estado', 'Completado')
            ->groupByRaw('DATE(fecha)')
            ->orderBy('fecha')
            ->get()
            ->map(function ($item) {
                return [
                    'fecha' => Carbon::parse($item->fecha)->format('Y-m-d'),
                    'total' => (float) $item->total,
                ];
            });

        // Si es un solo día, aseguramos que haya al menos un registro
        if ($startDate->isSameDay($endDate) && $ventasDiarias->isEmpty()) {
            $ventasDiarias->push([
                'fecha' => $startDate->format('Y-m-d'),
                'total' => 0.0,
            ]);
        }

        // Productos más vendidos (top 5)
        $masVendidos = DetallePedido::selectRaw('producto_id, SUM(cantidad) as total_vendido')
            ->whereHas('pedido', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->where('estado', 'Entregado');
            })
            ->groupBy('producto_id')
            ->orderBy('total_vendido', 'desc')
            ->take(5)
            ->with('producto')
            ->get()
            ->map(function ($item) {
                return [
                    'nombre' => $item->producto->nombre,
                    'total_vendido' => (int) $item->total_vendido,
                ];
            });

        // Ventas por categoría
        $ventasPorCategoria = DetallePedido::selectRaw('categorias.nombre, SUM(detalle_pedido.cantidad) as total_vendido')
            ->join('productos', 'detalle_pedido.producto_id', '=', 'productos.id')
            ->join('categoria_producto', 'productos.id', '=', 'categoria_producto.producto_id')
            ->join('categorias', 'categoria_producto.categoria_id', '=', 'categorias.id')
            ->whereHas('pedido', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->where('estado', 'Entregado');
            })
            ->groupBy('categorias.nombre')
            ->orderBy('total_vendido', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'nombre' => $item->nombre,
                    'total_vendido' => (int) $item->total_vendido,
                ];
            });

        // Pedidos por estado
        $pedidosPorEstado = Pedido::selectRaw('estado, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('estado')
            ->get()
            ->map(function ($item) {
                return [
                    'estado' => $item->estado,
                    'total' => (int) $item->total,
                ];
            });

        // Ingresos por hora del día
        $ingresosPorHora = Pago::selectRaw('HOUR(fecha) as hora, SUM(monto) as total')
            ->whereBetween('fecha', [$startDate, $endDate])
            ->where('estado', 'Completado')
            ->groupByRaw('HOUR(fecha)')
            ->orderBy('hora')
            ->get()
            ->map(function ($item) {
                return [
                    'hora' => sprintf('%02d:00-%02d:00', $item->hora, $item->hora + 1),
                    'total' => (float) $item->total,
                ];
            });

        // Si es un solo día, rellenar las horas vacías con 0
        if ($startDate->isSameDay($endDate)) {
            $allHours = collect(range(0, 23))->map(function ($hour) {
                return sprintf('%02d:00-%02d:00', $hour, $hour + 1);
            });
            $ingresosPorHora = $allHours->map(function ($hour) use ($ingresosPorHora) {
                $existing = $ingresosPorHora->firstWhere('hora', $hour);
                return [
                    'hora' => $hour,
                    'total' => $existing ? (float) $existing['total'] : 0.0,
                ];
            });
        }

        return compact(
            'ventasDiarias',
            'masVendidos',
            'ventasPorCategoria',
            'pedidosPorEstado',
            'ingresosPorHora'
        );
    }

    public function index(Request $request)
    {
        // Rango de fechas: día actual por defecto si no se proporciona
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));

        // Obtener datos de estadísticas
        $data = $this->getStatisticsData($startDate, $endDate);

        // Preparar datos para Chart.js
        $ventasDiariasChart = [
            'type' => 'line',
            'data' => [
                'labels' => $data['ventasDiarias']->pluck('fecha')->toArray(),
                'datasets' => [[
                    'label' => 'Ventas Diarias ($)',
                    'data' => $data['ventasDiarias']->pluck('total')->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                ]],
            ],
            'options' => [
                'scales' => [
                    'y' => ['beginAtZero' => true, 'title' => ['display' => true, 'text' => 'Ventas ($)']],
                    'x' => ['title' => ['display' => true, 'text' => 'Fecha']],
                ],
            ],
        ];

        $masVendidosChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $data['masVendidos']->pluck('nombre')->toArray(),
                'datasets' => [[
                    'label' => 'Unidades Vendidas',
                    'data' => $data['masVendidos']->pluck('total_vendido')->toArray(),
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                ]],
            ],
            'options' => [
                'scales' => [
                    'y' => ['beginAtZero' => true, 'title' => ['display' => true, 'text' => 'Unidades']],
                    'x' => ['title' => ['display' => true, 'text' => 'Producto']],
                ],
            ],
        ];

        $ventasPorCategoriaChart = [
            'type' => 'pie',
            'data' => [
                'labels' => $data['ventasPorCategoria']->pluck('nombre')->toArray(),
                'datasets' => [[
                    'label' => 'Unidades Vendidas',
                    'data' => $data['ventasPorCategoria']->pluck('total_vendido')->toArray(),
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#6b7280'],
                ]],
            ],
            'options' => ['plugins' => ['legend' => ['position' => 'top']]],
        ];

        $pedidosPorEstadoChart = [
            'type' => 'doughnut',
            'data' => [
                'labels' => $data['pedidosPorEstado']->pluck('estado')->toArray(),
                'datasets' => [[
                    'label' => 'Pedidos',
                    'data' => $data['pedidosPorEstado']->pluck('total')->toArray(),
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                ]],
            ],
            'options' => ['plugins' => ['legend' => ['position' => 'top']]],
        ];

        $ingresosPorHoraChart = [
            'type' => 'bar',
            'data' => [
                'labels' => $data['ingresosPorHora']->pluck('hora')->toArray(),
                'datasets' => [[
                    'label' => 'Ingresos por Hora ($)',
                    'data' => $data['ingresosPorHora']->pluck('total')->toArray(),
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#059669',
                    'borderWidth' => 1,
                ]],
            ],
            'options' => [
                'scales' => [
                    'y' => ['beginAtZero' => true, 'title' => ['display' => true, 'text' => 'Ingresos ($)']],
                    'x' => ['title' => ['display' => true, 'text' => 'Hora del Día']],
                ],
            ],
        ];

        return view('Administrador.graficos.graficos', compact(
            'ventasDiariasChart',
            'masVendidosChart',
            'ventasPorCategoriaChart',
            'pedidosPorEstadoChart',
            'ingresosPorHoraChart',
            'startDate',
            'endDate'
        ));
    }

    public function exportPdf(Request $request)
    {
        // Rango de fechas: día actual por defecto si no se proporciona
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));

        // Obtener datos de estadísticas
        $data = $this->getStatisticsData($startDate, $endDate);

        // Generar el PDF
        $pdf = Pdf::loadView('Administrador.graficos.reporte-estadisticas', [
            'startDate' => Carbon::parse($startDate)->format('d/m/Y'),
            'endDate' => Carbon::parse($endDate)->format('d/m/Y'),
            'ventasDiarias' => $data['ventasDiarias'],
            'masVendidos' => $data['masVendidos'],
            'ventasPorCategoria' => $data['ventasPorCategoria'],
            'pedidosPorEstado' => $data['pedidosPorEstado'],
            'ingresosPorHora' => $data['ingresosPorHora'],
        ]);

        // Configurar opciones del PDF
        $pdf->setPaper('A4', 'portrait');

        // Descargar el PDF
        return $pdf->download('Reporte_Estadisticas_' . date('Ymd') . '.pdf');
    }
}