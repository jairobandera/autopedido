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
            ['Big Gourmet', 'Hamburguesa de 200g con queso cheddar, lechuga, tomate y salsa especial.', 650.00, '/storage/imagenes/productos/2OiE8PKIJ6OO69D3cKHA2nYvMYI4QdkL3GViGPaf.jpg'],
            ['Veggie Burger', 'Hamburguesa vegana con medallón de lentejas, rúcula y mayonesa vegana.', 580.00, '/storage/imagenes/productos/owsefBAUwfcX27u4qN2C8K4ziNLdPUvQPw1md8ey.jpg'],
            ['Bacon Cheese', 'Hamburguesa con panceta crocante, queso mozzarella y salsa BBQ.', 700.00, '/storage/imagenes/productos/tgij9MXPcNOjHDsYJSdztS7v9BaNzM6BPdnxCP9W.jpg'],

            // Pizzas
            ['Pizza Margherita', 'Pizza con salsa de tomate, mozzarella fresca y albahaca.', 900.00, '/storage/imagenes/productos/yBTmN47ouejO7xEdUEK9hj1jt7OTudGzkxe6G9JA.jpg'],
            ['Pizza Pepperoni', 'Pizza con salsa de tomate, mozzarella y pepperoni picante.', 950.00, '/storage/imagenes/productos/42ztPTd2DQMuv4t34JTZj7nNntDLGGlGfcaVoPbb.jpg'],
            ['Pizza Cuatro Quesos', 'Pizza con mozzarella, parmesano, gorgonzola y cheddar.', 1000.00, '/storage/imagenes/productos/DsatbQnu7N9O0otdH0rwJogMaeVFcle8qcmvGJWU.jpg'],

            // Empanadas
            ['Empanada de Carne', 'Empanada de carne cortada a cuchillo con cebolla y morrón.', 150.00, '/storage/imagenes/productos/MZ4vaynU5BYxDUuQTI6OLzK0xH5GdjE7vtvUHG2d.jpg'],
            ['Empanada de Jamón y Queso', 'Empanada rellena de jamón cocido y mozzarella.', 140.00, '/storage/imagenes/productos/KyBQMkwyou2m01g1AyBHdKC04xMguibut7Jb477M.jpg'],
            ['Empanada Caprese', 'Empanada con tomate, mozzarella y albahaca.', 145.00, '/storage/imagenes/productos/xUer07ak7LhwegZnOuUjdWLXdnVVZX37js9koZWO.jpg'],

            // Bebidas
            ['Coca-Cola 500ml', 'Refresco de cola bien frío.', 200.00, '/storage/imagenes/productos/wqeeK4KMhBLp9WPY66c7a34LmaJR19awRzviIfAy.jpg'],
            ['Café Latte', 'Café espresso con leche vaporizada.', 250.00, '/storage/imagenes/productos/e8BTEpVHIdxMBU72WdNO6vO7S3BYb0GUdVVa3O8t.jpg'],
            ['Jugo de Naranja', 'Jugo natural de naranjas frescas.', 220.00, '/storage/imagenes/productos/9YLE2WkvDR05aB95NuX0zqAdynkdj9gyeH0Nr39n.jpg'],
            ['Cerveza Artesanal IPA', 'Cerveza artesanal con notas cítricas.', 350.00, '/storage/imagenes/productos/SIHVuJsEsBCXIN2jiy4aDiMTjFOYVyqxzis03bTv.jpg'],

            // Postres
            ['Tiramisú', 'Postre italiano con café, mascarpone y cacao.', 300.00, '/storage/imagenes/productos/922DPqSuWZIjcwl3RqJ8vM6BR7de2f5xJ8PDlyKh.jpg'],
            ['Flan Casero', 'Flan con dulce de leche y crema.', 250.00, '/storage/imagenes/productos/f3h53i8GSJ7IRuNSBOxZG0nsupePsXRTUzQ2f2pU.jpg'],
            ['Cheesecake de Frutos Rojos', 'Tarta de queso con salsa de frutos rojos.', 320.00, '/storage/imagenes/productos/NvS4oGL1qRB2nkjGvlciJ0w2iVgg80I3HalagKaH.jpg'],

            // Ensaladas
            ['Ensalada Mediterránea', 'Mix de hojas verdes, tomate cherry, aceitunas y feta.', 400.00, '/storage/imagenes/productos/U6g0SYdvvQlAZ7mOAPPNTqX8e0Rm4bAK0hM9Lwss.jpg'],
            ['Ensalada de Pollo', 'Lechuga, pollo grillado, croutones y aderezo César.', 450.00, '/storage/imagenes/productos/M8KzhnyG92iLBVJAYLnqsb3nvUo4tcXN7hK5n6Yx.jpg'],

            // Snacks
            ['Papas Fritas Clásicas', 'Papas crujientes con sal.', 200.00, '/storage/imagenes/productos/Yn6fVhvdWAPZqcEChdHdNVOYrbmBIPe3mOjo3sWW.jpg'],
            ['Bastones de Mozzarella', 'Palitos de mozzarella empanizados.', 280.00, '/storage/imagenes/productos/vu2L24jA22LxLmKQGYdvmGXrFyxpXflBIHZv9cZt.jpg'],

            // Desayunos
            ['Tostadas con Palta', 'Tostadas de pan artesanal con palta y huevo.', 350.00, '/storage/imagenes/productos/Z9khoESY9T9RdbEpsGIF7KgANno33r10wbtrhfJ9.jpg'],
            ['Croissant de Jamón y Queso', 'Croissant relleno de jamón cocido y queso.', 300.00, '/storage/imagenes/productos/I9u69YtymHNiKBTzRYDYvw7I9wVWZ2CnckIVLOpD.jpg'],

            // Platos Fuertes
            ['Milanesa Napolitana', 'Milanesa de ternera con jamón, queso y salsa de tomate.', 800.00, '/storage/imagenes/productos/diHmj9CXGm6aSndOC0triRXz13fg3aeqdcasezsz.jpg'],
            ['Ravioles de Ricota', 'Pasta rellena de ricota con salsa pomodoro.', 700.00, '/storage/imagenes/productos/nwzuFnLz9Y43WrxlshG9IgNZbpo1jqgp9sCcclvU.jpg'],

            // Opciones Veganas
            ['Bowl Vegano', 'Quinoa, palta, garbanzos y vegetales asados.', 600.00, '/storage/imagenes/productos/pVMMczK1rtHtCxZOEiBGc0atyzq98X0OBr8lguTq.jpg'],
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