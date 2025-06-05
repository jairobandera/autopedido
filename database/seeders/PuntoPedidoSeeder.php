<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PuntoPedido;
use App\Models\Pedido;
use App\Models\Cliente;
use Carbon\Carbon;

class PuntoPedidoSeeder extends Seeder
{
    public function run()
    {
        $clientes = Cliente::all();
        $pedidos  = Pedido::all();

        if ($clientes->isEmpty() || $pedidos->isEmpty()) {
            return;
        }

        // Para cada pedido existente, vamos a asignarle un cliente al azar
        foreach ($pedidos as $pedido) {
            // Escogemos un cliente aleatorio:
            $cliente = $clientes->random();

            // Calculamos puntos: por ejemplo 1 punto cada $10
            $puntos = floor($pedido->total / 10);
            // Aseguramos que sean al menos 10 y no más de 100 (sólo como ejemplo)
            $puntos = max(10, min($puntos, 100));

            // Definimos el tipo aleatorio
            $tipo = fake()->randomElement(['Canjeo', 'Redencion']);

            // Fecha igual a la fecha del pedido (o + – algunas horas si quisieras)
            $fecha = $pedido->created_at;

            PuntoPedido::create([
                'cliente_id' => $cliente->id,
                'pedido_id'  => $pedido->id,
                'cantidad'   => $puntos,
                'tipo'       => $tipo,
                'fecha'      => $fecha,
            ]);
        }
    }
}
