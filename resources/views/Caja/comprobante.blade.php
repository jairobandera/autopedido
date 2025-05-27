@extends('layouts.app-caja')

@section('content')
    <!-- Estilos para tickets de 80mm -->
    <style>
        @media print {
            @page {
                size: 80mm auto;
                margin: 5mm;
            }

            body {
                width: 80mm;
            }

            #btn-imprimir-comprobante,
            #btn-imprimir-por-las-dudas {
                display: none !important;
            }
        }
    </style>

    <div class="container text-center my-5">
        <h3>Comprobante de Pedido</h3>
        <p>Código: <strong>{{ $pedido->codigo }}</strong></p>
        <!-- Aquí se generará el barcode -->
        <svg id="barcode"></svg>
        <div class="mt-4">
            <button id="btn-imprimir-comprobante" class="btn btn-primary me-2">
                Imprimir Comprobante
            </button>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- JsBarcode desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        // Genera el código de barras en formato CODE128
        JsBarcode("#barcode", "{{ $pedido->codigo }}", {
            format: "CODE128",
            width: 2,
            height: 50,
            displayValue: false
        });

        // Auto-print al cargar
        window.onload = () => window.print();

        // Handlers de impresión manual
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('btn-imprimir-comprobante')
                .addEventListener('click', () => window.print());
        });
    </script>
@endsection