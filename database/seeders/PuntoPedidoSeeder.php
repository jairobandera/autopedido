<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PuntoPedido;
use App\Models\Pedido;
use App\Models\Cliente;

class PuntoPedidoSeeder extends Seeder
{
    public function run()
    {
        $clientes = Cliente::all();
        $pedidos = Pedido::all();

        if ($clientes->isEmpty() || $pedidos->isEmpty()) {
            return;
        }

        foreach ($clientes as $cliente) {
            // Solo pedidos del usuario asociado al cliente
            $pedidosCliente = $pedidos->where('usuario_id', $cliente->usuario_id)
                                      ->take(rand(1, min(3, $pedidos->where('usuario_id', $cliente->usuario_id)->count())));

            foreach ($pedidosCliente as $pedido) {
                // Calcular puntos proporcionales al total del pedido (1 punto por cada $10, por ejemplo)
                $puntos = floor($pedido->total / 10);
                $puntos = max(10, min($puntos, 100)); // Entre 10 y 100 puntos

                PuntoPedido::create([
                    'cliente_id' => $cliente->id,
                    'pedido_id' => $pedido->id,
                    'puntos' => $puntos,
                    'tipo' => fake()->randomElement(['Acumulacion', 'Redencion']),
                    'fecha' => $pedido->created_at, // Coincide con la fecha del pedido
                ]);
            }
        }
    }
}