<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PedidoSeeder extends Seeder
{
    public function run()
    {
        // Tomamos solo usuarios que sean Cajero (o Administrador, si te interesa)
        $cajeros = Usuario::whereIn('rol', ['Cajero', 'Administrador'])->get();

        if ($cajeros->isEmpty()) {
            return;
        }

        foreach ($cajeros as $cajero) {
            $numPedidos = rand(1, 3);
            for ($i = 0; $i < $numPedidos; $i++) {
                $estado = fake()->randomElement(['Cancelado', 'Recibido', 'En Preparacion', 'Listo', 'Entregado']);
                $metodoPago = fake()->randomElement(['Efectivo', 'Tarjeta']);
                // Generamos una fecha aleatoria dentro de los últimos 30 días
                $fecha = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

                Pedido::create([
                    'usuario_id' => $cajero->id,               // ahora asocia al cajero que genera el pedido
                    'total' => 0,                         // se actualizará luego cuando crees DetallePedido
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
