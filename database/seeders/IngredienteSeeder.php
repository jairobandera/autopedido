<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingrediente;

class IngredienteSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 15; $i++) {
            Ingrediente::create([
                'nombre' => ucfirst(fake()->unique()->word()),
                'descripcion' => fake()->sentence(5),
                'activo' => true,
            ]);
        }
    }
}
