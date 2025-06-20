@extends('layouts.app-caja')

@section('content')
    <style>
        @media print {
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
                width: 76mm;
                margin: 0 auto;
            }
            @page {
                size: 76mm auto;
                margin: 0;
            }
            #btn-imprimir-comprobante {
                display: none !important;
            }
        }
        #ticket {
            font-family: 'Poppins', sans-serif;
            padding: 10px;
            max-width: 76mm;
            margin: 0 auto;
        }
        #ticket h3, #ticket p {
            margin: 0;
            padding: 5px 0;
        }
        #ticket hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        #ticket img {
            max-width: 60mm;
            margin: 0 auto 10px;
        }
    </style>

    <div id="ticket" class="container text-center my-5">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid">
        <h3 class="fw-bold">Comprobante de Pedido</h3>
        <p>Código: <strong>{{ $pedido->codigo }}</strong></p>
        <svg id="barcode"></svg>

        <hr>

        @if ($pedido->cliente)
            @php
                $cliente = $pedido->cliente;
            @endphp

            <p>
                <strong>Cliente:</strong><br>
                Cédula: {{ $cliente->cedula }}<br>
                Nombre: {{ $cliente->nombre }} {{ $cliente->apellido }}
            </p>
            <hr>

            @if ($pedido->puntoPedido)
                @php
                    $puntosGenerados = $pedido->puntoPedido->cantidad;
                    $puntosTotales = $cliente->puntos;
                @endphp

                <p><strong>Puntos generados:</strong> {{ $puntosGenerados }}</p>
                <p><strong>Puntos totales:</strong> {{ $puntosTotales }}</p>
                <hr>
            @else
                <p class="text-muted"><em>Muchas gracias por su compra</em></p>
                <hr>
            @endif
        @else
            <p class="text-muted"><em>Muchas gracias por su compra</em></p>
            <hr>
        @endif

        <div class="mt-4">
            <button id="btn-imprimir-comprobante" class="btn btn-primary rounded-pill">
                <i class="bi bi-printer me-1"></i> Imprimir Comprobante
            </button>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            JsBarcode("#barcode", "{{ $pedido->codigo }}", {
                format: "CODE128",
                width: 2,
                height: 50,
                displayValue: false
            });
            // window.print(); // Comentado para permitir vista previa antes de imprimir
        });
        document.getElementById('btn-imprimir-comprobante')
            .addEventListener('click', () => window.print());
    </script>
@endsection