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
            }

            @page {
                size: 76mm auto;
                margin: 0;
            }

            #btn-imprimir-comprobante {
                display: none !important;
            }
        }
    </style>

    <div id="ticket" class="container text-center my-5">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width:60mm; margin:0 auto 10px; display:block;">
        <h3>Comprobante de Pedido</h3>
        <p>Código: <strong>{{ $pedido->codigo }}</strong></p>
        <svg id="barcode"></svg>

        <hr style="border-top:1px dashed #000; margin:10px 0;">

        {{-- Mostrar siempre el cliente asociado si existe --}}
        @if ($pedido->cliente)
            @php
                $cliente = $pedido->cliente;
            @endphp

            <p>
                <strong>Cliente:</strong><br>
                Cédula: {{ $cliente->cedula }}<br>
                Nombre: {{ $cliente->nombre }} {{ $cliente->apellido }}
            </p>
            <hr style="border-top:1px dashed #000; margin:10px 0;">

            {{-- Si ya se generaron puntos (cuando el pedido fue marcado como Entregado) --}}
            @if ($pedido->puntoPedido)
                @php
                    $puntosGenerados = $pedido->puntoPedido->cantidad;
                    $puntosTotales = $cliente->puntos;
                @endphp

                <p>
                    <strong>Puntos generados:</strong> {{ $puntosGenerados }}
                </p>
                <p>
                    <strong>Puntos totales:</strong> {{ $puntosTotales }}
                </p>
                <hr style="border-top:1px dashed #000; margin:10px 0;">
            @else
                <p><em>Muchas gracias por su compra</em></p>
                <hr style="border-top:1px dashed #000; margin:10px 0;">
            @endif

        @else
            <p><em>Muchas gracias por su compra</em></p>
            <hr style="border-top:1px dashed #000; margin:10px 0;">
        @endif

        <div class="mt-4">
            <button id="btn-imprimir-comprobante" class="btn btn-primary">
                Imprimir Comprobante
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
            window.print();
        });
        document.getElementById('btn-imprimir-comprobante')
            .addEventListener('click', () => window.print());
    </script>
@endsection