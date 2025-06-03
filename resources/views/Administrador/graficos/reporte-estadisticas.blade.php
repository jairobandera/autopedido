<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Estadísticas - Eatsy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 16px;
            color: #555;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #777;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Estadísticas de Ventas</h1>
        <p>Eatsy - Panel de Administración</p>
        <p>Período: {{ $startDate }} al {{ $endDate }}</p>
        <p>Generado el: {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="section-title">Introducción</div>
    <p>
        Este reporte presenta las estadísticas de ventas y pedidos de [EMPRESA] para el período del {{ $startDate }} al {{ $endDate }}. Incluye información sobre ventas diarias, productos más vendidos, ventas por categoría, distribución de pedidos por estado, e ingresos por hora. Los datos están diseñados para proporcionar una visión clara y precisa del desempeño comercial.
    </p>

    <div class="section-title">Ventas Diarias</div>
    <p>La siguiente tabla muestra las ventas diarias en dólares estadounidenses (USD).</p>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Total Ventas (USD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ventasDiarias as $venta)
                <tr>
                    <td>{{ $venta['fecha'] }}</td>
                    <td>{{ number_format($venta['total'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="section-title">Productos Más Vendidos</div>
    <p>Los cinco productos más vendidos, ordenados por unidades vendidas.</p>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Unidades Vendidas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($masVendidos as $producto)
                <tr>
                    <td>{{ $producto['nombre'] }}</td>
                    <td>{{ $producto['total_vendido'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Ventas por Categoría</div>
    <p>Distribución de ventas por categoría de productos.</p>
    <table>
        <thead>
            <tr>
                <th>Categoría</th>
                <th>Unidades Vendidas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ventasPorCategoria as $categoria)
                <tr>
                    <td>{{ $categoria['nombre'] }}</td>
                    <td>{{ $categoria['total_vendido'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="section-title">Pedidos por Estado</div>
    <p>Cantidad de pedidos clasificados por estado.</p>
    <table>
        <thead>
            <tr>
                <th>Estado</th>
                <th>Cantidad de Pedidos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedidosPorEstado as $pedido)
                <tr>
                    <td>{{ $pedido['estado'] }}</td>
                    <td>{{ $pedido['total'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Ingresos por Hora</div>
    <p>Ingresos generados por hora del día (USD).</p>
    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Total Ingresos (USD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ingresosPorHora as $ingreso)
                <tr>
                    <td>{{ $ingreso['hora'] }}</td>
                    <td>{{ number_format($ingreso['total'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ now()->year }} [EMPRESA] - Todos los derechos reservados</p>
    </div>
</body>
</html>