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
            // Máximo 4 productos por pedido
            $max = min(4, $productos->count());
            $items = $productos->random(rand(1, $max));

            foreach ($items as $prod) {
                $cant = rand(1, 3);
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $prod->id,
                    'fecha_hora' => now(),
                    'cantidad' => $cant,
                    'subtotal' => $prod->precio * $cant,
                ]);
            }
        }
    }
}
