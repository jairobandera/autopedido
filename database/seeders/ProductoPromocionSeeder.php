<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use App\Models\Promocion;

class ProductoPromocionSeeder extends Seeder
{
    public function run()
    {
        $productos = Producto::all()->keyBy('nombre');
        $promociones = Promocion::all()->keyBy('nombre');

        $asignaciones = [
            // Martes 2x1 en Pizzas
            'Pizza Margherita' => ['Martes 2x1 en Pizzas'],
            'Pizza Pepperoni' => ['Martes 2x1 en Pizzas'],
            'Pizza Cuatro Quesos' => ['Martes 2x1 en Pizzas'],

            // Happy Hour Bebidas
            'Coca-Cola 500ml' => ['Happy Hour Bebidas'],
            'Café Latte' => ['Happy Hour Bebidas'],
            'Jugo de Naranja' => ['Happy Hour Bebidas'],
            'Cerveza Artesanal IPA' => ['Happy Hour Bebidas'],

            // Combo Familiar
            'Big Gourmet' => ['Combo Familiar'],
            'Bacon Cheese' => ['Combo Familiar'],
            'Papas Fritas Clásicas' => ['Combo Familiar'],
            'Coca-Cola 500ml' => ['Combo Familiar'],

            // Lunes Saludable
            'Veggie Burger' => ['Lunes Saludable'],
            'Ensalada Mediterránea' => ['Lunes Saludable'],
            'Ensalada de Pollo' => ['Lunes Saludable'],
            'Bowl Vegano' => ['Lunes Saludable'],
            'Jugo de Naranja' => ['Lunes Saludable'],

            // Jueves de Empanadas
            'Empanada de Carne' => ['Jueves de Empanadas'],
            'Empanada de Jamón y Queso' => ['Jueves de Empanadas'],
            'Empanada Caprese' => ['Jueves de Empanadas'],

            // Fin de Semana Dulce
            'Tiramisú' => ['Fin de Semana Dulce'],
            'Flan Casero' => ['Fin de Semana Dulce'],
            'Cheesecake de Frutos Rojos' => ['Fin de Semana Dulce'],

            // Día del Niño
            'Empanada de Jamón y Queso' => ['Día del Niño'],
            'Papas Fritas Clásicas' => ['Día del Niño'],
            'Jugo de Naranja' => ['Día del Niño'],
        ];

        foreach ($asignaciones as $productoNombre => $promocionesAsignadas) {
            if (isset($productos[$productoNombre])) {
                $producto = $productos[$productoNombre];
                foreach ($promocionesAsignadas as $promocionNombre) {
                    if (isset($promociones[$promocionNombre])) {
                        DB::table('producto_promocion')->insert([
                            'producto_id' => $producto->id,
                            'promocion_id' => $promociones[$promocionNombre]->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}