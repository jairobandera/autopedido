<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\Usuario;
use Carbon\Carbon;

class PedidoSeeder extends Seeder
{
    public function run()
    {
        $clientes = Usuario::where('rol', 'Cliente')->get();

        if ($clientes->isEmpty()) {
            return;
        }

        foreach ($clientes as $cliente) {
            $numPedidos = rand(1, 3);
            for ($i = 0; $i < $numPedidos; $i++) {
                $estado = fake()->randomElement(['Cancelado', 'Recibido', 'En Preparacion', 'Listo', 'Entregado']);
                $metodoPago = fake()->randomElement(['Efectivo', 'MercadoPago']);
                $fecha = Carbon::now()->subDays(rand(0, 30)); // Pedidos en los últimos 30 días

                Pedido::create([
                    'usuario_id' => $cliente->id,
                    'total' => 0, // Se calculará en DetallePedidoSeeder
                    'metodo_pago' => $metodoPago,
                    'estado' => $estado,
                    'codigo' => strtoupper('ORD-' . fake()->unique()->numberBetween(100, 999) . '-' . fake()->randomLetter() . fake()->randomLetter()),
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ]);
            }
        }
    }
}