<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\Usuario;
use App\Models\Producto;
use Carbon\Carbon;

class PedidoSeeder extends Seeder
{
    public function run()
    {
        // Buscar usuarios con rol 'Cliente'
        $usuarios = Usuario::where('rol', 'Cliente')->get();

        // Validar que existan usuarios
        if ($usuarios->isEmpty()) {
            return;
        }

        // Contador para códigos únicos
        $pedidoCounter = 1;

        foreach ($usuarios as $usuario) {
            // Generar 1 a 3 pedidos por cliente
            $numPedidos = rand(1, 3);
            for ($i = 0; $i < $numPedidos; $i++) {
                // Fecha del pedido (últimos 30 días)
                $fecha = Carbon::now()->subDays(rand(0, 30));

                // Código único (PED-YYYYMMDD-NNN)
                $codigo = sprintf('PED-%s-%03d', $fecha->format('Ymd'), $pedidoCounter++);

                // Crear el pedido con total temporal (se actualizará en DetallePedidoSeeder)
                Pedido::create([
                    'usuario_id' => $usuario->id,
                    'total' => 0, // Temporal, se calculará en DetallePedidoSeeder
                    'metodo_pago' => fake()->randomElement(['Efectivo', 'MercadoPago']),
                    'estado' => $this->determinarEstado($fecha),
                    'codigo' => $codigo,
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ]);
            }
        }
    }

    /**
     * Determinar el estado del pedido según la antigüedad.
     */
    private function determinarEstado($fecha)
    {
        $diasAntiguedad = Carbon::now()->diffInDays($fecha);
        if ($diasAntiguedad <= 2) {
            return fake()->randomElement(['Recibido', 'En Preparacion']);
        } elseif ($diasAntiguedad <= 5) {
            return fake()->randomElement(['En Preparacion', 'Listo']);
        }
        return 'Entregado';
    }
}