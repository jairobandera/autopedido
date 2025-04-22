<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promocion;
use App\Models\Producto;

class PromocionSeeder extends Seeder
{
    public function run()
    {
        $productos = Producto::all();

        foreach ($productos->take(10) as $producto) {
            Promocion::create([
                'producto_id' => $producto->id,
                'nombre' => ucfirst(fake()->word()) . ' Promo',
                'descripcion' => fake()->sentence(5),
                'descuento' => rand(5, 30), // porcentaje de descuento
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addDays(rand(5, 15)),
                'activo' => true,
            ]);
        }
    }
}
