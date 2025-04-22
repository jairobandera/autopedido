<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 20; $i++) {
            Producto::create([
                'nombre' => ucfirst(fake()->unique()->word()),
                'descripcion' => fake()->sentence(6),
                'precio' => fake()->randomFloat(2, 100, 1000),
                'imagen' => fake()->imageUrl(640, 480, 'food', true),
                'activo' => true,
            ]);
        }
    }
}
