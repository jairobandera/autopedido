<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\Usuario;

class PedidoSeeder extends Seeder
{
    public function run()
    {
        $clientes = Usuario::where('rol', 'Cliente')->get();

        foreach ($clientes as $cliente) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                Pedido::create([
                    'usuario_id' => $cliente->id,
                    'total' => fake()->randomFloat(2, 200, 1500),
                    'metodo_pago' => fake()->randomElement(['Efectivo', 'MercadoPago']),
                    'estado' => fake()->randomElement([
                        'Cancelado',
                        'Recibido',
                        'En Preparacion', // sin tilde, coincide con tu migración
                        'Listo',
                        'Entregado',
                    ]),
                    'codigo' => strtoupper(fake()->bothify('ORD-###-??')),
                ]);
            }
        }
    }
}
