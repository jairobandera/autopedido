<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingrediente;

class IngredienteSeeder extends Seeder
{
    public function run()
    {
        $ingredientes = [
            ['nombre' => 'Carne de Res', 'descripcion' => 'Carne de res de alta calidad, jugosa y bien sazonada.'],
            ['nombre' => 'Pechuga de Pollo', 'descripcion' => 'Pechuga de pollo tierna, grillada o empanizada.'],
            ['nombre' => 'Lechuga', 'descripcion' => 'Hojas de lechuga fresca, crujiente y lavada.'],
            ['nombre' => 'Tomate', 'descripcion' => 'Rodajas de tomate fresco, maduro y jugoso.'],
            ['nombre' => 'Pepinillos', 'descripcion' => 'Pepinillos encurtidos, agridulces y crujientes.'],
            ['nombre' => 'Cebolla', 'descripcion' => 'Cebolla fresca, cortada en rodajas finas o caramelizada.'],
            ['nombre' => 'Queso Cheddar', 'descripcion' => 'Queso cheddar fundido, cremoso y sabroso.'],
            ['nombre' => 'Bacon', 'descripcion' => 'Tiras de bacon crujiente, ahumado y salado.'],
            ['nombre' => 'Pan Artesanal', 'descripcion' => 'Pan artesanal horneado, suave y ligeramente tostado.'],
            ['nombre' => 'Salsa BBQ', 'descripcion' => 'Salsa barbacoa dulce y ahumada.'],
            ['nombre' => 'Mayonesa', 'descripcion' => 'Mayonesa cremosa, clásica o vegana.'],
            ['nombre' => 'Ketchup', 'descripcion' => 'Ketchup clásico, dulce y ligeramente ácido.'],
            ['nombre' => 'Mostaza', 'descripcion' => 'Mostaza suave con un toque picante.'],
            ['nombre' => 'Jalapeños', 'descripcion' => 'Jalapeños frescos o encurtidos, con un toque picante.'],
            ['nombre' => 'Queso Mozzarella', 'descripcion' => 'Queso mozzarella fresco, ideal para pizzas y empanadas.'],
            ['nombre' => 'Salsa de Tomate', 'descripcion' => 'Salsa de tomate casera, elaborada con hierbas frescas.'],
            ['nombre' => 'Albahaca', 'descripcion' => 'Hojas de albahaca fresca, aromática y vibrante.'],
            ['nombre' => 'Queso Parmesano', 'descripcion' => 'Queso parmesano rallado, intenso y salado.'],
            ['nombre' => 'Queso Gorgonzola', 'descripcion' => 'Queso gorgonzola, cremoso con sabor fuerte.'],
            ['nombre' => 'Aceitunas', 'descripcion' => 'Aceitunas negras o verdes, suaves y sabrosas.'],
            ['nombre' => 'Tomate Cherry', 'descripcion' => 'Tomates cherry frescos, dulces y jugosos.'],
            ['nombre' => 'Queso Feta', 'descripcion' => 'Queso feta desmenuzado, salado y cremoso.'],
            ['nombre' => 'Rúcula', 'descripcion' => 'Hojas de rúcula fresca, con sabor ligeramente picante.'],
            ['nombre' => 'Palta', 'descripcion' => 'Palta madura, cremosa y rica en sabor.'],
            ['nombre' => 'Huevo', 'descripcion' => 'Huevo cocido o frito, fresco y de granja.'],
            ['nombre' => 'Jamón Cocido', 'descripcion' => 'Jamón cocido en finas lonchas, suave y sabroso.'],
            ['nombre' => 'Ricota', 'descripcion' => 'Ricota fresca, suave y cremosa para pastas.'],
            ['nombre' => 'Quinoa', 'descripcion' => 'Quinoa cocida, ligera y nutritiva.'],
            ['nombre' => 'Garbanzos', 'descripcion' => 'Garbanzos cocidos, ideales para ensaladas y bowls.'],
            ['nombre' => 'Vegetales Asados', 'descripcion' => 'Mix de vegetales asados (zapallo, berenjena, zucchini).'],
            ['nombre' => 'Mascarpone', 'descripcion' => 'Queso mascarpone cremoso, ideal para postres.'],
            ['nombre' => 'Dulce de Leche', 'descripcion' => 'Dulce de leche artesanal, dulce y cremoso.'],
            ['nombre' => 'Frutos Rojos', 'descripcion' => 'Mix de frutos rojos frescos (frambuesas, arándanos).'],
        ];

        foreach ($ingredientes as $ingrediente) {
            Ingrediente::create([
                'nombre' => $ingrediente['nombre'],
                'descripcion' => $ingrediente['descripcion'],
                'activo' => true,
            ]);
        }
    }
}