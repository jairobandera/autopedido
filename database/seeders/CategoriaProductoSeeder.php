<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaProductoSeeder extends Seeder
{
    public function run()
    {
        $asociaciones = [
            // Producto 1: Combo Familiar (categoria_id: 1)
            ['producto_id' => 1, 'categoria_id' => 1], // Combos
            ['producto_id' => 1, 'categoria_id' => 2], // Hamburguesas (contiene hamburguesas)
            ['producto_id' => 1, 'categoria_id' => 8], // Entradas (contiene papas fritas)

            // Producto 2: Combo Pizza (categoria_id: 1)
            ['producto_id' => 2, 'categoria_id' => 1], // Combos
            ['producto_id' => 2, 'categoria_id' => 3], // Pizzas (contiene pizza)
            ['producto_id' => 2, 'categoria_id' => 5], // Bebidas (contiene bebida)

            // Producto 3: Hamburguesa Clásica (categoria_id: 2)
            ['producto_id' => 3, 'categoria_id' => 2], // Hamburguesas

            // Producto 4: Hamburguesa BBQ (categoria_id: 2)
            ['producto_id' => 4, 'categoria_id' => 2], // Hamburguesas

            // Producto 5: Pizza Margherita (categoria_id: 3)
            ['producto_id' => 5, 'categoria_id' => 3], // Pizzas

            // Producto 6: Pizza Pepperoni (categoria_id: 3)
            ['producto_id' => 6, 'categoria_id' => 3], // Pizzas

            // Producto 7: Empanada de Carne (categoria_id: 4)
            ['producto_id' => 7, 'categoria_id' => 4], // Empanadas
            ['producto_id' => 7, 'categoria_id' => 8], // Entradas (puede considerarse entrada)

            // Producto 8: Empanada de Pollo (categoria_id: 4)
            ['producto_id' => 8, 'categoria_id' => 4], // Empanadas
            ['producto_id' => 8, 'categoria_id' => 8], // Entradas

            // Producto 9: Gaseosa Cola 500ml (categoria_id: 5)
            ['producto_id' => 9, 'categoria_id' => 5], // Bebidas

            // Producto 10: Agua Mineral 500ml (categoria_id: 5)
            ['producto_id' => 10, 'categoria_id' => 5], // Bebidas

            // Producto 11: Flan Casero (categoria_id: 6)
            ['producto_id' => 11, 'categoria_id' => 6], // Postres

            // Producto 12: Tiramisú (categoria_id: 6)
            ['producto_id' => 12, 'categoria_id' => 6], // Postres

            // Producto 13: Ensalada César (categoria_id: 7)
            ['producto_id' => 13, 'categoria_id' => 7], // Ensaladas
            ['producto_id' => 13, 'categoria_id' => 10], // Platos Principales (puede ser un plato principal)

            // Producto 14: Ensalada Caprese (categoria_id: 7)
            ['producto_id' => 14, 'categoria_id' => 7], // Ensaladas

            // Producto 15: Papas Fritas (categoria_id: 8)
            ['producto_id' => 15, 'categoria_id' => 8], // Entradas

            // Producto 16: Nachos con Queso (categoria_id: 8)
            ['producto_id' => 16, 'categoria_id' => 8], // Entradas
            ['producto_id' => 16, 'categoria_id' => 9], // Complementos (guacamole como complemento)

            // Producto 17: Salsa BBQ (categoria_id: 9)
            ['producto_id' => 17, 'categoria_id' => 9], // Complementos

            // Producto 18: Guacamole (categoria_id: 9)
            ['producto_id' => 18, 'categoria_id' => 9], // Complementos

            // Producto 19: Milanesa con Puré (categoria_id: 10)
            ['producto_id' => 19, 'categoria_id' => 10], // Platos Principales

            // Producto 20: Pasta Boloñesa (categoria_id: 10)
            ['producto_id' => 20, 'categoria_id' => 10], // Platos Principales
        ];

        // Insertar las asociaciones en la tabla pivote
        foreach ($asociaciones as $asociacion) {
            DB::table('categoria_producto')->insert([
                'producto_id' => $asociacion['producto_id'],
                'categoria_id' => $asociacion['categoria_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}