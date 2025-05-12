<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\Pedido;

class PagoSeeder extends Seeder
{
    public function run()
    {
        $pedidos = Pedido::all();

        if ($pedidos->isEmpty()) {
            return;
        }

        foreach ($pedidos as $pedido) {
            // Determinar estado del pago según el estado del pedido
            $estadoPago = $this->determinarEstadoPago($pedido->estado);

            Pago::create([
                'pedido_id' => $pedido->id,
                'tipo' => $pedido->metodo_pago,
                'monto' => $pedido->total,
                'fecha' => $pedido->created_at,
                'estado' => $estadoPago,
            ]);
        }
    }

    /**
     * Determinar el estado del pago según el estado del pedido.
     */
    private function determinarEstadoPago($estadoPedido)
    {
        if ($estadoPedido === 'Entregado') {
            return 'Completado';
        } elseif (in_array($estadoPedido, ['Recibido', 'En Preparacion'])) {
            return 'Pendiente';
        }
        return fake()->randomElement(['Completado', 'Pendiente']); // Para 'Listo'
    }
}