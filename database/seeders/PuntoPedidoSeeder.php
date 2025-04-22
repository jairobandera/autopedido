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

        foreach ($clientes as $cliente) {
            $pedidosCliente = $pedidos->where('usuario_id', $cliente->usuario_id)->take(rand(1, 3));

            foreach ($pedidosCliente as $pedido) {
                PuntoPedido::create([
                    'cliente_id' => $cliente->id,
                    'pedido_id' => $pedido->id,
                    'puntos' => rand(10, 100),
                    'tipo' => fake()->randomElement(['Acumulacion', 'Redencion']),
                    'fecha' => now(),
                ]);
            }
        }
    }
}
