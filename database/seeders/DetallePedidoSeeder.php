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

        // Si no hay pedidos o productos, salimos
        if ($pedidos->isEmpty() || $productos->isEmpty()) {
            return;
        }

        foreach ($pedidos as $pedido) {
            // máximo 4 ítems o el total de productos disponibles
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
