<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        $productosReales = [
            ['Big Mac', 'La clásica hamburguesa con doble carne, queso y salsa especial.', 480.00, 'https://via.placeholder.com/640x480?text=Big+Mac'],
            ['McNuggets (6 pzas)', 'Bocados de pollo empanizado, crujientes y deliciosos.', 350.00, 'https://via.placeholder.com/640x480?text=McNuggets+6'],
            ['Papas Fritas Grandes', 'Papas doradas y crujientes, acompañante perfecto.', 220.00, 'https://via.placeholder.com/640x480?text=Papas+Fritas'],
            ['Quarter Pounder', 'Hamburguesa de res con queso, cebolla y pepinillos.', 420.00, 'https://via.placeholder.com/640x480?text=Quarter+Pounder'],
            ['Filet-O-Fish', 'Pescado empanizado con queso y salsa tártara.', 390.00, 'https://via.placeholder.com/640x480?text=Filet-O-Fish'],
            ['McFlurry Oreo', 'Helado cremoso con trozos de galleta Oreo.', 280.00, 'https://via.placeholder.com/640x480?text=McFlurry+Oreo'],
            ['Ensalada César', 'Lechuga fresca con pollo y aderezo César.', 340.00, 'https://via.placeholder.com/640x480?text=Ensalada+Cesar'],
            ['Happy Meal', 'Combo infantil con juguete y papitas pequeñas.', 450.00, 'https://via.placeholder.com/640x480?text=Happy+Meal'],
            ['Brownie de Chocolate', 'Postre de chocolate con corazón fundido.', 180.00, 'https://via.placeholder.com/640x480?text=Brownie'],
            ['Batido de Fresa', 'Leche y fresas naturales, bebida fresca.', 300.00, 'https://via.placeholder.com/640x480?text=Batido+Fresa'],
            ['Cheeseburger', 'Hamburguesa sencilla con queso cheddar.', 320.00, 'https://via.placeholder.com/640x480?text=Cheeseburger'],
            ['McNuggets (9 pzas)', 'Trozos de pollo crujiente en porción mediana.', 450.00, 'https://via.placeholder.com/640x480?text=McNuggets+9'],
            ['Wrap de Pollo', 'Tortilla rellena de pollo, lechuga y salsa.', 360.00, 'https://via.placeholder.com/640x480?text=Wrap+Pollo'],
            ['Muffin de Arándanos', 'Panecillo suave con arándanos frescos.', 200.00, 'https://via.placeholder.com/640x480?text=Muffin+Arandanos'],
            ['Café Americano', 'Café filtrado, sabor intenso.', 180.00, 'https://via.placeholder.com/640x480?text=Cafe+Americano'],
            ['Sprite 500ml', 'Refresco lima-limón bien frío.', 150.00, 'https://via.placeholder.com/640x480?text=Sprite'],
            ['Agua Mineral', 'Agua pura con gas.', 120.00, 'https://via.placeholder.com/640x480?text=Agua+Mineral'],
            ['Ensalada de Frutas', 'Mix de frutas de temporada.', 260.00, 'https://via.placeholder.com/640x480?text=Ensalada+Frutas'],
            ['Pan de Ajo', 'Pan con mantequilla de ajo y hierbas.', 140.00, 'https://via.placeholder.com/640x480?text=Pan+Ajo'],
            ['McCafé Capuccino', 'Café espumoso con un toque de canela.', 260.00, 'https://via.placeholder.com/640x480?text=Capuccino'],
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
