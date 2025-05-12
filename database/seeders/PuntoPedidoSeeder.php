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
            // Seleccionar pedidos del cliente (usuario_id coincide)
            $pedidosCliente = $pedidos->where('usuario_id', $cliente->usuario_id)->take(rand(1, 3));

            foreach ($pedidosCliente as $pedido) {
                // Calcular puntos: 1 punto por $1 (redondeado)
                $puntos = floor($pedido->total);

                // Determinar tipo: 'Acumulacion' por defecto, 'Redencion' para algunos
                $tipo = $this->determinarTipoPunto($cliente, $puntos);

                PuntoPedido::create([
                    'cliente_id' => $cliente->id,
                    'pedido_id' => $pedido->id,
                    'puntos' => $puntos,
                    'tipo' => $tipo,
                    'fecha' => $pedido->created_at,
                ]);

                // Actualizar puntos_totales en Cliente
                $this->actualizarPuntosCliente($cliente, $puntos, $tipo);
            }
        }
    }

    /**
     * Determinar el tipo de punto (Acumulacion o Redencion).
     */
    private function determinarTipoPunto($cliente, $puntos)
    {
        // 20% de probabilidad de Redencion si el cliente tiene suficientes puntos
        if ($cliente->puntos_totales >= $puntos && fake()->boolean(20)) {
            return 'Redencion';
        }
        return 'Acumulacion';
    }

    /**
     * Actualizar puntos_totales en la tabla Cliente.
     */
    private function actualizarPuntosCliente($cliente, $puntos, $tipo)
    {
        if ($tipo === 'Acumulacion') {
            $cliente->puntos_totales += $puntos;
        } elseif ($tipo === 'Redencion') {
            $cliente->puntos_totales -= $puntos;
        }
        $cliente->save();
    }
}