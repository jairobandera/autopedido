@extends('layouts.app-caja')

@section('content')
    <!-- Estilos para tickets de 80mm -->
    <style>
        @media print {

            /* Sólo mostramos el #ticket */
            body * {
                visibility: hidden;
            }

            #ticket,
            #ticket * {
                visibility: visible;
            }

            #ticket {
                position: absolute;
                top: 0;
                left: 0;
                text-align: center;
            }

            @page {
                size: 76mm auto;
                margin: 0;
            }

            #btn-imprimir-comprobante,
            #btn-imprimir-por-las-dudas {
                display: none !important;
            }
        }
    </style>

    <div class="container text-center my-5" id="ticket">
        {{-- Logo centrado --}}
        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width: 60mm; margin: 0 auto 10px; display: block;">
        
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