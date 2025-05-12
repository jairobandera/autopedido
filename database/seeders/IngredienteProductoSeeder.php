<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredienteProductoSeeder extends Seeder
{
    public function run()
    {
        // Asociaciones lógicas entre productos e ingredientes
        // Producto IDs (1-20) y Ingrediente IDs (1-20) basados en ProductoSeeder e IngredienteSeeder
        $asociaciones = [
            // Producto 1: Combo Familiar (Hamburguesas, Papas Fritas, Gaseosas)
            ['producto_id' => 1, 'ingrediente_id' => 1], // Carne
            ['producto_id' => 1, 'ingrediente_id' => 5], // Lechuga
            ['producto_id' => 1, 'ingrediente_id' => 6], // Tomate
            ['producto_id' => 1, 'ingrediente_id' => 4], // Queso Cheddar
            ['producto_id' => 1, 'ingrediente_id' => 12], // Papas

            // Producto 2: Combo Pizza (Pizza Muzzarella, Bebida)
            ['producto_id' => 2, 'ingrediente_id' => 9], // Salsa de Tomate
            ['producto_id' => 2, 'ingrediente_id' => 3], // Queso Muzzarella

            // Producto 3: Hamburguesa Clásica
            ['producto_id' => 3, 'ingrediente_id' => 1], // Carne
            ['producto_id' => 3, 'ingrediente_id' => 5], // Lechuga
            ['producto_id' => 3, 'ingrediente_id' => 6], // Tomate
            ['producto_id' => 3, 'ingrediente_id' => 4], // Queso Cheddar

            // Producto 4: Hamburguesa BBQ
            ['producto_id' => 4, 'ingrediente_id' => 1], // Carne
            ['producto_id' => 4, 'ingrediente_id' => 4], // Queso Cheddar
            ['producto_id' => 4, 'ingrediente_id' => 7], // Cebolla
            ['producto_id' => 4, 'ingrediente_id' => 8], // Bacon
            ['producto_id' => 4, 'ingrediente_id' => 14], // Salsa BBQ

            // Producto 5: Pizza Margherita
            ['producto_id' => 5, 'ingrediente_id' => 9], // Salsa de Tomate
            ['producto_id' => 5, 'ingrediente_id' => 3], // Queso Muzzarella
            ['producto_id' => 5, 'ingrediente_id' => 11], // Albahaca

            // Producto 6: Pizza Pepperoni
            ['producto_id' => 6, 'ingrediente_id' => 9], // Salsa de Tomate
            ['producto_id' => 6, 'ingrediente_id' => 3], // Queso Muzzarella
            ['producto_id' => 6, 'ingrediente_id' => 10], // Pepperoni

            // Producto 7: Empanada de Carne
            ['producto_id' => 7, 'ingrediente_id' => 1], // Carne
            ['producto_id' => 7, 'ingrediente_id' => 7], // Cebolla
            ['producto_id' => 7, 'ingrediente_id' => 3], // Queso Muzzarella

            // Producto 8: Empanada de Pollo
            ['producto_id' => 8, 'ingrediente_id' => 2], // Pollo
            ['producto_id' => 8, 'ingrediente_id' => 7], // Cebolla

            // Producto 9: Gaseosa Cola 500ml (sin ingredientes)
            // Producto 10: Agua Mineral 500ml (sin ingredientes)

            // Producto 11: Flan Casero
            ['producto_id' => 11, 'ingrediente_id' => 15], // Crema Chantilly

            // Producto 12: Tiramisú
            ['producto_id' => 12, 'ingrediente_id' => 16], // Mascarpone
            ['producto_id' => 12, 'ingrediente_id' => 17], // Café

            // Producto 13: Ensalada César
            ['producto_id' => 13, 'ingrediente_id' => 5], // Lechuga
            ['producto_id' => 13, 'ingrediente_id' => 2], // Pollo
            ['producto_id' => 13, 'ingrediente_id' => 18], // Crutones
            ['producto_id' => 13, 'ingrediente_id' => 19], // Aderezo César

            // Producto 14: Ensalada Caprese
            ['producto_id' => 14, 'ingrediente_id' => 6], // Tomate
            ['producto_id' => 14, 'ingrediente_id' => 3], // Queso Muzzarella
            ['producto_id' => 14, 'ingrediente_id' => 11], // Albahaca

            // Producto 15: Papas Fritas
            ['producto_id' => 15, 'ingrediente_id' => 12], // Papas

            // Producto 16: Nachos con Queso
            ['producto_id' => 16, 'ingrediente_id' => 4], // Queso Cheddar
            ['producto_id' => 16, 'ingrediente_id' => 13], // Guacamole

            // Producto 17: Salsa BBQ
            ['producto_id' => 17, 'ingrediente_id' => 14], // Salsa BBQ

            // Producto 18: Guacamole
            ['producto_id' => 18, 'ingrediente_id' => 13], // Guacamole

            // Producto 19: Milanesa con Puré
            ['producto_id' => 19, 'ingrediente_id' => 1], // Carne
            ['producto_id' => 19, 'ingrediente_id' => 12], // Papas

            // Producto 20: Pasta Boloñesa
            ['producto_id' => 20, 'ingrediente_id' => 1], // Carne
            ['producto_id' => 20, 'ingrediente_id' => 9], // Salsa de Tomate
            ['producto_id' => 20, 'ingrediente_id' => 20], // Queso Parmesano
        ];

        // Insertar las asociaciones en la tabla pivote
        foreach ($asociaciones as $asociacion) {
            DB::table('ingrediente_producto')->insert([
                'producto_id' => $asociacion['producto_id'],
                'ingrediente_id' => $asociacion['ingrediente_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}