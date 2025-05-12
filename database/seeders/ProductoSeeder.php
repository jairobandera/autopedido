<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        $productos = [
            // Combos (categoria_id: 1)
            [
                'nombre' => 'Combo Familiar',
                'descripcion' => 'Dos hamburguesas clásicas, papas fritas grandes y dos gaseosas.',
                'precio' => 22.50,
                'imagen' => 'images/combos/combo_familiar.jpg',
                'activo' => true,
            ],
            [
                'nombre' => 'Combo Pizza',
                'descripcion' => 'Pizza mediana de muzzarella con una bebida de 500ml.',
                'precio' => 18.00,
                'imagen' => 'images/combos/combo_pizza.jpg',
                'activo' => true,
            ],

            // Hamburguesas (categoria_id: 2)
            [
                'nombre' => 'Hamburguesa Clásica',
                'descripcion' => 'Carne 100% vacuna, lechuga, tomate, queso y salsa especial.',
                'precio' => 8.50,
                'imagen' => 'images/hamburguesas/clasica.jpg',
                'activo' => true,
            ],
            [
                'nombre' => 'Hamburguesa BBQ',
                'descripcion' => 'Carne, queso cheddar, bacon, cebolla caramelizada y salsa BBQ.',
                'precio' => 9.75,
                'imagen' => 'images/hamburguesas/bbq.jpg',
                'activo' => true,
            ],

            // Pizzas (categoria_id: 3)
            [
                'nombre' => 'Pizza Margherita',
                'descripcion' => 'Salsa de tomate, muzzarella fresca y albahaca.',
                'precio' => 12.00,
                'imagen' => 'images/pizzas/margherita.jpg',
                'activo' => true,
            ],
            [
                'nombre' => 'Pizza Pepperoni',
                'descripcion' => 'Salsa de tomate, muzzarella y rodajas de pepperoni.',
                'precio' => 13.50,
                'imagen' => 'images/pizzas/pepperoni.jpg',
                'activo' => true,
            ],

            // Empanadas (categoria_id: 4)
            [
                'nombre' => 'Empanada de Carne',
                'descripcion' => 'Rellena de carne molida, cebolla y especias.',
                'precio' => 2.50,
                'imagen' => 'images/empanadas/carne.jpg',
                'activo' => true,
            ],
            [
                'nombre' => 'Empanada de Pollo',
                'descripcion' => 'Pollo desmenuzado con salsa criolla.',
                'precio' => 2.50,
                'imagen' => 'images/empanadas/pollo.jpg',
                'activo' => true,
            ],

            // Bebidas (categoria_id: 5)
            [
                'nombre' => 'Gaseosa Cola 500ml',
                'descripcion' => 'Refrescante gaseosa de cola.',
                'precio' => 2.00,
                'imagen' => 'images/bebidas/cola.jpg',
                'activo' => true,
            ],
            [
                'nombre' => 'Agua Mineral 500ml',
                'descripcion' => 'Agua mineral sin gas.',
                'precio' => 1.50,
                'imagen' => 'images/bebidas/agua.jpg',
                'activo' => true,
            ],

            // Postres (categoria_id: 6)
            [
                'nombre' => 'Flan Casero',
                'descripcion' => 'Flan con caramelo y crema chantilly.',
                'precio' => 4.00,
                'imagen' => 'images/postres/flan.jpg',
                'activo' => true,
            ],
            [
                'nombre' => 'Tiramisú',
                'descripcion' => 'Postre italiano con café, mascarpone y cacao.',
                'precio' => 5.50,
                'imagen' => 'images/postres/tiramisu.jpg',
                'activo' => true,
            ],

            // Ensaladas (categoria_id: 7)
            [
                'nombre' => 'Ensalada César',
                'descripcion' => 'Lechuga, pollo grillado, crutones y aderezo César.',
                'precio' => 7.50,
                'imagen' => 'images/ensaladas/cesar.jpg',
                'activo' => true,
            ],
            [
                'nombre' => 'Ensalada Caprese',
                'descripcion' => 'Tomate, muzzarella, albahaca y aceite de oliva.',
                'precio' => 6.50,
                'imagen' => 'images/ensaladas/caprese.jpg',
                'activo' => true,
            ],

            // Entradas (categoria_id: 8)
            [
                'nombre' => 'Papas Fritas',
                'descripcion' => 'Porción grande de papas crujientes.',
                'precio' => 4.00,
                'imagen' => 'images/entradas/papas.jpg',
                'activo' => true,
            ],
            [
                'nombre' => 'Nachos con Queso',
                'descripcion' => 'Nachos con salsa de queso y guacamole.',
                'precio' => 5.00,
                'imagen' => 'images/entradas/nachos.jpg',
                'activo' => true,
            ],

            // Complementos (categoria_id: 9)
            [
                'nombre' => 'Salsa BBQ',
                'descripcion' => 'Porción de salsa barbacoa casera.',
                'precio' => 1.00,
                'imagen' => 'images/complementos/bbq.jpg',
                'activo' => true,
            ],
            [
                'nombre' => 'Guacamole',
                'descripcion' => 'Porción de guacamole fresco.',
                'precio' => 1.50,
                'imagen' => 'images/complementos/guacamole.jpg',
                'activo' => true,
            ],

            // Platos Principales (categoria_id: 10)
            [
                'nombre' => 'Milanesa con Puré',
                'descripcion' => 'Milanesa de ternera con puré de papas.',
                'precio' => 10.50,
                'imagen' => 'images/platos/milanesa.jpg',
                'activo' => true,
            ],
            [
                'nombre' => 'Pasta Boloñesa',
                'descripcion' => 'Spaghetti con salsa de carne y queso parmesano.',
                'precio' => 11.00,
                'imagen' => 'images/platos/pasta.jpg',
                'activo' => true,
            ],
        ];

        //insertamos los productos en la base de datos
        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
