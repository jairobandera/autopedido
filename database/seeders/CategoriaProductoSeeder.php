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
        $productos = Producto::all();
        $categorias = Categoria::all();

        foreach ($productos as $producto) {
            $categoriasAleatorias = $categorias->random(rand(1, 3));
            foreach ($categoriasAleatorias as $categoria) {
                DB::table('categoria_producto')->insert([
                    'producto_id' => $producto->id,
                    'categoria_id' => $categoria->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
