<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;

class DetallePedidoSeeder extends Seeder
{
    public function run()
    {
        $pedidos = Pedido::all();
        $productos = Producto::all();

        if ($pedidos->isEmpty() || $productos->isEmpty()) {
            return;
        }

        foreach ($pedidos as $pedido) {
            // Generar 1 a 4 productos por pedido
            $numProductos = rand(1, 4);
            $productosAleatorios = $productos->random($numProductos);
            $total = 0;

            foreach ($productosAleatorios as $producto) {
                // Ajustar cantidad según el tipo de producto
                $cantidad = $producto->precio > 10 ? rand(1, 2) : rand(1, 3); // Menos unidades para productos caros
                $subtotal = $producto->precio * $cantidad;
                $total += $subtotal;

                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'fecha_hora' => $pedido->created_at,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal,
                ]);
            }

            // Actualizar el total del pedido
            $pedido->total = round($total, 2);
            $pedido->save();
        }
    }
}