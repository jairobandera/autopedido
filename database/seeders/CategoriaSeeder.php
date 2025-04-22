<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        $nombres = [
            'Combos', 'Hamburguesas', 'Pizzas', 'Empanadas', 'Bebidas',
            'Postres', 'Ensaladas', 'Snacks', 'Salsas', 'Desayunos'
        ];

        foreach ($nombres as $nombre) {
            Categoria::create([
                'nombre' => $nombre,
            ]);
        }
    }
}
