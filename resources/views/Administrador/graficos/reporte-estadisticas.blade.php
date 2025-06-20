<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Estadísticas - Eatsy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 50px auto;
            max-width: 1200px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #ff5722;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #ff5722;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 14px;
            color: #666;
        }
        .section-title {
            font-size: 20px;
            font-weight: 500;
            margin-top: 40px;
            margin-bottom: 15px;
            border-bottom: 2px solid #ff5722;
            padding-bottom: 5px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #ff5722;
            color: white;
            font-weight: 500;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .page-break {
            page-break-before: always;
        }
        @media print {
            .page-break { page-break-before: always; }
            body { margin: 0; padding: 0; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Estadísticas de Ventas</h1>
        <p>Eatsy - Panel de Administración</p>
        <p>Período: {{ $startDate }} al {{ $endDate }}</p>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }} (Hora local)</p>
    </div>

    <div class="section-title">Introducción</div>
    <p>
        Este reporte presenta las estadísticas de ventas y pedidos de Eatsy para el período del {{ $startDate }} al {{ $endDate }}. Incluye información sobre ventas diarias, productos más vendidos, ventas por categoría, distribución de pedidos por estado, e ingresos por hora. Los datos están diseñados para proporcionar una visión clara y precisa del desempeño comercial.
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
        <p>© {{ now()->year }} Eatsy - Todos los derechos reservados</p>
    </div>
</body>
</html>