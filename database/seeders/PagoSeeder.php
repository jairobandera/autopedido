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

        foreach ($pedidos as $pedido) {
            Pago::create([
                'pedido_id' => $pedido->id,
                'tipo' => $pedido->metodo_pago,
                'monto' => $pedido->total,
                'fecha' => now(),
                'estado' => fake()->randomElement(['Completado', 'Pendiente', 'Fallido']),
            ]);
        }
    }
}
