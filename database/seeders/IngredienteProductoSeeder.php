<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use App\Models\Ingrediente;

class IngredienteProductoSeeder extends Seeder
{
    public function run()
    {
        $productos = Producto::all()->keyBy('nombre');
        $ingredientes = Ingrediente::all()->keyBy('nombre');

        $asignaciones = [
            // Hamburguesas
            'Big Gourmet' => [
                ['nombre' => 'Carne de Res', 'es_obligatorio' => true],
                ['nombre' => 'Pan Artesanal', 'es_obligatorio' => true],
                ['nombre' => 'Queso Cheddar', 'es_obligatorio' => false],
                ['nombre' => 'Lechuga', 'es_obligatorio' => false],
                ['nombre' => 'Tomate', 'es_obligatorio' => false],
                ['nombre' => 'Mayonesa', 'es_obligatorio' => false],
            ],
            'Veggie Burger' => [
                ['nombre' => 'Pan Artesanal', 'es_obligatorio' => true],
                ['nombre' => 'Rúcula', 'es_obligatorio' => false],
                ['nombre' => 'Mayonesa', 'es_obligatorio' => false],
                ['nombre' => 'Tomate', 'es_obligatorio' => false],
                ['nombre' => 'Quinoa', 'es_obligatorio' => true],
            ],
            'Bacon Cheese' => [
                ['nombre' => 'Carne de Res', 'es_obligatorio' => true],
                ['nombre' => 'Pan Artesanal', 'es_obligatorio' => true],
                ['nombre' => 'Bacon', 'es_obligatorio' => true],
                ['nombre' => 'Queso Mozzarella', 'es_obligatorio' => true],
                ['nombre' => 'Salsa BBQ', 'es_obligatorio' => false],
            ],
            // Pizzas
            'Pizza Margherita' => [
                ['nombre' => 'Salsa de Tomate', 'es_obligatorio' => true],
                ['nombre' => 'Queso Mozzarella', 'es_obligatorio' => true],
                ['nombre' => 'Albahaca', 'es_obligatorio' => false],
            ],
            'Pizza Pepperoni' => [
                ['nombre' => 'Salsa de Tomate', 'es_obligatorio' => true],
                ['nombre' => 'Queso Mozzarella', 'es_obligatorio' => true],
                ['nombre' => 'Pepperoni', 'es_obligatorio' => true],
            ],
            'Pizza Cuatro Quesos' => [
                ['nombre' => 'Salsa de Tomate', 'es_obligatorio' => true],
                ['nombre' => 'Queso Mozzarella', 'es_obligatorio' => true],
                ['nombre' => 'Queso Parmesano', 'es_obligatorio' => true],
                ['nombre' => 'Queso Gorgonzola', 'es_obligatorio' => true],
                ['nombre' => 'Queso Cheddar', 'es_obligatorio' => true],
            ],
            // Empanadas
            'Empanada de Carne' => [
                ['nombre' => 'Carne de Res', 'es_obligatorio' => true],
                ['nombre' => 'Cebolla', 'es_obligatorio' => false],
                ['nombre' => 'Morrón', 'es_obligatorio' => false],
            ],
            'Empanada de Jamón y Queso' => [
                ['nombre' => 'Jamón Cocido', 'es_obligatorio' => true],
                ['nombre' => 'Queso Mozzarella', 'es_obligatorio' => true],
            ],
            'Empanada Caprese' => [
                ['nombre' => 'Queso Mozzarella', 'es_obligatorio' => true],
                ['nombre' => 'Tomate', 'es_obligatorio' => true],
                ['nombre' => 'Albahaca', 'es_obligatorio' => false],
            ],
            // Bebidas (sin ingredientes, se omiten)
            // Postres
            'Tiramisú' => [
                ['nombre' => 'Mascarpone', 'es_obligatorio' => true],
                ['nombre' => 'Café', 'es_obligatorio' => true],
                ['nombre' => 'Cacao', 'es_obligatorio' => false],
            ],
            'Flan Casero' => [
                ['nombre' => 'Dulce de Leche', 'es_obligatorio' => true],
                ['nombre' => 'Crema', 'es_obligatorio' => false],
            ],
            'Cheesecake de Frutos Rojos' => [
                ['nombre' => 'Queso Crema', 'es_obligatorio' => true],
                ['nombre' => 'Frutos Rojos', 'es_obligatorio' => true],
            ],
            // Ensaladas
            'Ensalada Mediterránea' => [
                ['nombre' => 'Lechuga', 'es_obligatorio' => true],
                ['nombre' => 'Tomate Cherry', 'es_obligatorio' => true],
                ['nombre' => 'Aceitunas', 'es_obligatorio' => false],
                ['nombre' => 'Queso Feta', 'es_obligatorio' => false],
            ],
            'Ensalada de Pollo' => [
                ['nombre' => 'Lechuga', 'es_obligatorio' => true],
                ['nombre' => 'Pechuga de Pollo', 'es_obligatorio' => true],
                ['nombre' => 'Croutones', 'es_obligatorio' => false],
                ['nombre' => 'Aderezo César', 'es_obligatorio' => false],
            ],
            // Snacks
            'Papas Fritas Clásicas' => [
                ['nombre' => 'Papa', 'es_obligatorio' => true],
                ['nombre' => 'Sal', 'es_obligatorio' => false],
            ],
            'Bastones de Mozzarella' => [
                ['nombre' => 'Queso Mozzarella', 'es_obligatorio' => true],
                ['nombre' => 'Pan Rallado', 'es_obligatorio' => true],
            ],
            // Desayunos
            'Tostadas con Palta' => [
                ['nombre' => 'Pan Artesanal', 'es_obligatorio' => true],
                ['nombre' => 'Palta', 'es_obligatorio' => true],
                ['nombre' => 'Huevo', 'es_obligatorio' => false],
            ],
            'Croissant de Jamón y Queso' => [
                ['nombre' => 'Jamón Cocido', 'es_obligatorio' => true],
                ['nombre' => 'Queso Mozzarella', 'es_obligatorio' => true],
                ['nombre' => 'Masa de Croissant', 'es_obligatorio' => true],
            ],
            // Platos Fuertes
            'Milanesa Napolitana' => [
                ['nombre' => 'Carne de Res', 'es_obligatorio' => true],
                ['nombre' => 'Queso Mozzarella', 'es_obligatorio' => true],
                ['nombre' => 'Salsa de Tomate', 'es_obligatorio' => true],
                ['nombre' => 'Jamón Cocido', 'es_obligatorio' => false],
            ],
            'Ravioles de Ricota' => [
                ['nombre' => 'Ricota', 'es_obligatorio' => true],
                ['nombre' => 'Salsa de Tomate', 'es_obligatorio' => true],
                ['nombre' => 'Queso Parmesano', 'es_obligatorio' => false],
            ],
            // Opciones Veganas
            'Bowl Vegano' => [
                ['nombre' => 'Quinoa', 'es_obligatorio' => true],
                ['nombre' => 'Palta', 'es_obligatorio' => true],
                ['nombre' => 'Garbanzos', 'es_obligatorio' => true],
                ['nombre' => 'Vegetales Asados', 'es_obligatorio' => false],
            ],
        ];

        foreach ($asignaciones as $productoNombre => $ingredientesAsignados) {
            if (isset($productos[$productoNombre])) {
                $producto = $productos[$productoNombre];
                foreach ($ingredientesAsignados as $ingredienteData) {
                    if (isset($ingredientes[$ingredienteData['nombre']])) {
                        DB::table('ingrediente_producto')->insert([
                            'producto_id' => $producto->id,
                            'ingrediente_id' => $ingredientes[$ingredienteData['nombre']]->id,
                            'es_obligatorio' => $ingredienteData['es_obligatorio'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}