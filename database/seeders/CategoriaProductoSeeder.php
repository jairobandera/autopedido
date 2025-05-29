<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class CategoriaProductoSeeder extends Seeder
{
    public function run()
    {
        $categorias = Categoria::all()->keyBy('nombre');
        $productos = Producto::all();

        $asignaciones = [
            // Hamburguesas
            'Big Gourmet' => ['Hamburguesas'],
            'Veggie Burger' => ['Hamburguesas', 'Opciones Vegetarianas', 'Opciones Veganas'],
            'Bacon Cheese' => ['Hamburguesas'],

            // Pizzas
            'Pizza Margherita' => ['Pizzas'],
            'Pizza Pepperoni' => ['Pizzas'],
            'Pizza Cuatro Quesos' => ['Pizzas'],

            // Empanadas
            'Empanada de Carne' => ['Empanadas'],
            'Empanada de Jamón y Queso' => ['Empanadas'],
            'Empanada Caprese' => ['Empanadas', 'Opciones Vegetarianas'],

            // Bebidas
            'Coca-Cola 500ml' => ['Bebidas'],
            'Café Latte' => ['Bebidas', 'Cafetería'],
            'Jugo de Naranja' => ['Bebidas'],
            'Cerveza Artesanal IPA' => ['Bebidas', 'Bebidas Alcohólicas'],

            // Postres
            'Tiramisú' => ['Postres'],
            'Flan Casero' => ['Postres'],
            'Cheesecake de Frutos Rojos' => ['Postres'],

            // Ensaladas
            'Ensalada Mediterránea' => ['Ensaladas', 'Opciones Vegetarianas'],
            'Ensalada de Pollo' => ['Ensaladas'],

            // Snacks
            'Papas Fritas Clásicas' => ['Snacks'],
            'Bastones de Mozzarella' => ['Snacks', 'Entradas'],

            // Desayunos
            'Tostadas con Palta' => ['Desayunos', 'Opciones Vegetarianas'],
            'Croissant de Jamón y Queso' => ['Desayunos'],

            // Platos Fuertes
            'Milanesa Napolitana' => ['Platos Fuertes'],
            'Ravioles de Ricota' => ['Platos Fuertes', 'Pastas'],

            // Opciones Veganas
            'Bowl Vegano' => ['Opciones Veganas', 'Opciones Vegetarianas'],
        ];

        foreach ($productos as $producto) {
            $categoriasAsignadas = $asignaciones[$producto->nombre] ?? ['Especiales del Día']; // Fallback por si falta asignación
            foreach ($categoriasAsignadas as $categoriaNombre) {
                if (isset($categorias[$categoriaNombre])) {
                    DB::table('categoria_producto')->insert([
                        'producto_id' => $producto->id,
                        'categoria_id' => $categorias[$categoriaNombre]->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}