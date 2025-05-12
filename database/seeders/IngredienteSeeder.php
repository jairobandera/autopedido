<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingrediente;

class IngredienteSeeder extends Seeder
{
    public function run()
    {
        $ingredientes = [
            'Carne de Res',
            'Pechuga de Pollo',
            'Lechuga',
            'Tomate',
            'Pepinillos',
            'Cebolla',
            'Queso Cheddar',
            'Bacon',
            'Pan Artesanal',
            'Salsa BBQ',
            'Mayonesa',
            'Ketchup',
            'Mostaza',
            'Jalapeños',
            'Queso Mozzarella'
        ];

        foreach ($ingredientes as $nombre) {
            Ingrediente::create([
                'nombre' => $nombre,
                'descripcion' => "$nombre fresco y sabroso",
                'activo' => true,
            ]);
        }
    }
}
