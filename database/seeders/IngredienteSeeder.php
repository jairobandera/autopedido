<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingrediente;

class IngredienteSeeder extends Seeder
{
    public function run()
    {
        // Lista de ingredientes realistas
        $ingredientes = [
            [
                'nombre' => 'Carne',
                'descripcion' => 'Carne 100% vacuna, ideal para hamburguesas y empanadas.',
                'activo' => true,
            ],
            [
                'nombre' => 'Pollo',
                'descripcion' => 'Pollo desmenuzado o grillado, perfecto para empanadas y ensaladas.',
                'activo' => true,
            ],
            [
                'nombre' => 'Queso Muzzarella',
                'descripcion' => 'Queso muzzarella fresco, usado en pizzas y empanadas.',
                'activo' => true,
            ],
            [
                'nombre' => 'Queso Cheddar',
                'descripcion' => 'Queso cheddar fundido, ideal para hamburguesas y nachos.',
                'activo' => true,
            ],
            [
                'nombre' => 'Lechuga',
                'descripcion' => 'Hojas frescas de lechuga, para hamburguesas y ensaladas.',
                'activo' => true,
            ],
            [
                'nombre' => 'Tomate',
                'descripcion' => 'Rodajas de tomate fresco, usado en hamburguesas y ensaladas.',
                'activo' => true,
            ],
            [
                'nombre' => 'Cebolla',
                'descripcion' => 'Cebolla fresca o caramelizada, para hamburguesas y empanadas.',
                'activo' => true,
            ],
            [
                'nombre' => 'Bacon',
                'descripcion' => 'Tiras crujientes de bacon, perfectas para hamburguesas.',
                'activo' => true,
            ],
            [
                'nombre' => 'Salsa de Tomate',
                'descripcion' => 'Salsa de tomate casera, base para pizzas.',
                'activo' => true,
            ],
            [
                'nombre' => 'Pepperoni',
                'descripcion' => 'Rodajas de pepperoni picante, ideal para pizzas.',
                'activo' => true,
            ],
            [
                'nombre' => 'Albahaca',
                'descripcion' => 'Hojas frescas de albahaca, para pizzas y ensaladas.',
                'activo' => true,
            ],
            [
                'nombre' => 'Papas',
                'descripcion' => 'Papas frescas para papas fritas o puré.',
                'activo' => true,
            ],
            [
                'nombre' => 'Guacamole',
                'descripcion' => 'Guacamole fresco, usado como complemento o en nachos.',
                'activo' => true,
            ],
            [
                'nombre' => 'Salsa BBQ',
                'descripcion' => 'Salsa barbacoa casera, ideal para hamburguesas y complementos.',
                'activo' => true,
            ],
            [
                'nombre' => 'Crema Chantilly',
                'descripcion' => 'Crema batida dulce, perfecta para postres como flan.',
                'activo' => true,
            ],
            [
                'nombre' => 'Mascarpone',
                'descripcion' => 'Queso mascarpone cremoso, usado en tiramisú.',
                'activo' => true,
            ],
            [
                'nombre' => 'Café',
                'descripcion' => 'Café molido, ingrediente clave para tiramisú.',
                'activo' => true,
            ],
            [
                'nombre' => 'Crutones',
                'descripcion' => 'Trozos de pan tostado, ideales para ensaladas César.',
                'activo' => true,
            ],
            [
                'nombre' => 'Aderezo César',
                'descripcion' => 'Aderezo cremoso para ensaladas César.',
                'activo' => true,
            ],
            [
                'nombre' => 'Queso Parmesano',
                'descripcion' => 'Queso parmesano rallado, para pastas y ensaladas.',
                'activo' => true,
            ],
        ];

        // Insertar los ingredientes en la base de datos
        foreach ($ingredientes as $ingrediente) {
            Ingrediente::create($ingrediente);
        }
    }
}
