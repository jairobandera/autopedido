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
            // Determinar el estado del pago según el estado del pedido
            $estadoPago = 'Pendiente';
            if ($pedido->estado === 'Cancelado') {
                $estadoPago = fake()->randomElement(['Pendiente', 'Fallido']);
            } elseif (in_array($pedido->estado, ['Listo', 'Entregado'])) {
                $estadoPago = 'Completado';
            } elseif ($pedido->estado === 'En Preparacion') {
                $estadoPago = fake()->randomElement(['Pendiente', 'Completado']);
            }

            Pago::create([
                'pedido_id' => $pedido->id,
                'tipo' => $pedido->metodo_pago, // Efectivo o MercadoPago
                'monto' => $pedido->total,
                'fecha' => $pedido->created_at, // Coincide con la fecha del pedido
                'estado' => $estadoPago,
            ]);
        }
    }
}