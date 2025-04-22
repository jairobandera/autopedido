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

        foreach ($pedidos as $pedido) {
            $productosAleatorios = $productos->random(rand(1, 4));

            foreach ($productosAleatorios as $producto) {
                $cantidad = rand(1, 3);
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'fecha_hora' => now(),
                    'cantidad' => $cantidad,
                    'subtotal' => $producto->precio * $cantidad,
                ]);
            }
        }
    }
}
