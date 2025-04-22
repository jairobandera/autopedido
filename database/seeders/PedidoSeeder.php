<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\Usuario;

class PedidoSeeder extends Seeder
{
    public function run()
    {
        $usuarios = Usuario::where('rol', 'Cliente')->get();

        foreach ($usuarios as $usuario) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                Pedido::create([
                    'usuario_id' => $usuario->id,
                    'total' => fake()->randomFloat(2, 200, 2000),
                    'metodo_pago' => fake()->randomElement(['Efectivo', 'MercadoPago']),
                    'estado' => fake()->randomElement(['Recibido', 'En Preparacion', 'Listo', 'Entregado']),
                    'codigo' => strtoupper(fake()->bothify('PED###??')),
                ]);
            }
        }
    }
}
