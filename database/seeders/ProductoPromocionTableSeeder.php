<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductoPromocionTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $asociaciones = [
            // Promoción 1: Descuento en Combos (aplica a productos de la categoría Combos)
            ['producto_id' => 1, 'promocion_id' => 1], // Combo Familiar
            ['producto_id' => 2, 'promocion_id' => 1], // Combo Pizza

            // Promoción 2: 2x1 en Empanadas (aplica a productos de la categoría Empanadas)
            ['producto_id' => 7, 'promocion_id' => 2], // Empanada de Carne
            ['producto_id' => 8, 'promocion_id' => 2], // Empanada de Pollo

            // Promoción 3: Verano de Pizzas (aplica a productos de la categoría Pizzas)
            ['producto_id' => 5, 'promocion_id' => 3], // Pizza Margherita
            ['producto_id' => 6, 'promocion_id' => 3], // Pizza Pepperoni

            // Promoción 4: Black Friday Gastronómico (aplica a todos los productos)
            ['producto_id' => 1, 'promocion_id' => 4], // Combo Familiar
            ['producto_id' => 2, 'promocion_id' => 4], // Combo Pizza
            ['producto_id' => 3, 'promocion_id' => 4], // Hamburguesa Clásica
            ['producto_id' => 4, 'promocion_id' => 4], // Hamburguesa BBQ
            ['producto_id' => 5, 'promocion_id' => 4], // Pizza Margherita
            ['producto_id' => 6, 'promocion_id' => 4], // Pizza Pepperoni
            ['producto_id' => 7, 'promocion_id' => 4], // Empanada de Carne
            ['producto_id' => 8, 'promocion_id' => 4], // Empanada de Pollo
            ['producto_id' => 9, 'promocion_id' => 4], // Gaseosa Cola 500ml
            ['producto_id' => 10, 'promocion_id' => 4], // Agua Mineral 500ml
            ['producto_id' => 11, 'promocion_id' => 4], // Flan Casero
            ['producto_id' => 12, 'promocion_id' => 4], // Tiramisú
            ['producto_id' => 13, 'promocion_id' => 4], // Ensalada César
            ['producto_id' => 14, 'promocion_id' => 4], // Ensalada Caprese
            ['producto_id' => 15, 'promocion_id' => 4], // Papas Fritas
            ['producto_id' => 16, 'promocion_id' => 4], // Nachos con Queso
            ['producto_id' => 17, 'promocion_id' => 4], // Salsa BBQ
            ['producto_id' => 18, 'promocion_id' => 4], // Guacamole
            ['producto_id' => 19, 'promocion_id' => 4], // Milanesa con Puré
            ['producto_id' => 20, 'promocion_id' => 4], // Pasta Boloñesa

            // Promoción 5: Postre de Cumpleaños (aplica a productos de la categoría Postres)
            ['producto_id' => 11, 'promocion_id' => 5], // Flan Casero
            ['producto_id' => 12, 'promocion_id' => 5], // Tiramisú
        ];

        // Insertar las asociaciones en la tabla pivote
        foreach ($asociaciones as $asociacion) {
            DB::table('producto_promocion')->insert([
                'producto_id' => $asociacion['producto_id'],
                'promocion_id' => $asociacion['promocion_id'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}