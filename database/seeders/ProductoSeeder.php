<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        $productosReales = [
            // Hamburguesas
            ['Big Gourmet', 'Hamburguesa de 200g con queso cheddar, lechuga, tomate y salsa especial.', 650.00, 'https://via.placeholder.com/640x480?text=Big+Gourmet'],
            ['Veggie Burger', 'Hamburguesa vegana con medallón de lentejas, rúcula y mayonesa vegana.', 580.00, 'https://via.placeholder.com/640x480?text=Veggie+Burger'],
            ['Bacon Cheese', 'Hamburguesa con panceta crocante, queso mozzarella y salsa BBQ.', 700.00, 'https://via.placeholder.com/640x480?text=Bacon+Cheese'],

            // Pizzas
            ['Pizza Margherita', 'Pizza con salsa de tomate, mozzarella fresca y albahaca.', 900.00, 'https://via.placeholder.com/640x480?text=Pizza+Margherita'],
            ['Pizza Pepperoni', 'Pizza con salsa de tomate, mozzarella y pepperoni picante.', 950.00, 'https://via.placeholder.com/640x480?text=Pizza+Pepperoni'],
            ['Pizza Cuatro Quesos', 'Pizza con mozzarella, parmesano, gorgonzola y cheddar.', 1000.00, 'https://via.placeholder.com/640x480?text=Pizza+Cuatro+Quesos'],

            // Empanadas
            ['Empanada de Carne', 'Empanada de carne cortada a cuchillo con cebolla y morrón.', 150.00, 'https://via.placeholder.com/640x480?text=Empanada+Carne'],
            ['Empanada de Jamón y Queso', 'Empanada rellena de jamón cocido y mozzarella.', 140.00, 'https://via.placeholder.com/640x480?text=Empanada+JYQ'],
            ['Empanada Caprese', 'Empanada con tomate, mozzarella y albahaca.', 145.00, 'https://via.placeholder.com/640x480?text=Empanada+Caprese'],

            // Bebidas
            ['Coca-Cola 500ml', 'Refresco de cola bien frío.', 200.00, 'https://via.placeholder.com/640x480?text=Coca+Cola'],
            ['Café Latte', 'Café espresso con leche vaporizada.', 250.00, 'https://via.placeholder.com/640x480?text=Cafe+Latte'],
            ['Jugo de Naranja', 'Jugo natural de naranjas frescas.', 220.00, 'https://via.placeholder.com/640x480?text=Jugo+Naranja'],
            ['Cerveza Artesanal IPA', 'Cerveza artesanal con notas cítricas.', 350.00, 'https://via.placeholder.com/640x480?text=Cerveza+IPA'],

            // Postres
            ['Tiramisú', 'Postre italiano con café, mascarpone y cacao.', 300.00, 'https://via.placeholder.com/640x480?text=Tiramisu'],
            ['Flan Casero', 'Flan con dulce de leche y crema.', 250.00, 'https://via.placeholder.com/640x480?text=Flan+Casero'],
            ['Cheesecake de Frutos Rojos', 'Tarta de queso con salsa de frutos rojos.', 320.00, 'https://via.placeholder.com/640x480?text=Cheesecake'],

            // Ensaladas
            ['Ensalada Mediterránea', 'Mix de hojas verdes, tomate cherry, aceitunas y feta.', 400.00, 'https://via.placeholder.com/640x480?text=Ensalada+Mediterranea'],
            ['Ensalada de Pollo', 'Lechuga, pollo grillado, croutones y aderezo César.', 450.00, 'https://via.placeholder.com/640x480?text=Ensalada+Pollo'],

            // Snacks
            ['Papas Fritas Clásicas', 'Papas crujientes con sal.', 200.00, 'https://via.placeholder.com/640x480?text=Papas+Fritas'],
            ['Bastones de Mozzarella', 'Palitos de mozzarella empanizados.', 280.00, 'https://via.placeholder.com/640x480?text=Mozzarella+Sticks'],

            // Desayunos
            ['Tostadas con Palta', 'Tostadas de pan artesanal con palta y huevo.', 350.00, 'https://via.placeholder.com/640x480?text=Tostadas+Palta'],
            ['Croissant de Jamón y Queso', 'Croissant relleno de jamón cocido y queso.', 300.00, 'https://via.placeholder.com/640x480?text=Croissant+JYQ'],

            // Platos Fuertes
            ['Milanesa Napolitana', 'Milanesa de ternera con jamón, queso y salsa de tomate.', 800.00, 'https://via.placeholder.com/640x480?text=Milanesa+Napolitana'],
            ['Ravioles de Ricota', 'Pasta rellena de ricota con salsa pomodoro.', 700.00, 'https://via.placeholder.com/640x480?text=Ravioles+Ricota'],

            // Opciones Veganas
            ['Bowl Vegano', 'Quinoa, palta, garbanzos y vegetales asados.', 600.00, 'https://via.placeholder.com/640x480?text=Bowl+Vegano'],
        ];

        foreach ($productosReales as $item) {
            Producto::create([
                'nombre'      => $item[0],
                'descripcion' => $item[1],
                'precio'      => $item[2],
                'imagen'      => $item[3],
                'activo'      => true,
            ]);
        }
    }
}