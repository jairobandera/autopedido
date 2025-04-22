<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Ingrediente;
use Illuminate\Support\Facades\DB;

class IngredienteProductoSeeder extends Seeder
{
    public function run()
    {
        $productos = Producto::all();
        $ingredientes = Ingrediente::all();

        foreach ($productos as $producto) {
            $ingredientesAleatorios = $ingredientes->random(rand(2, 5));
            foreach ($ingredientesAleatorios as $ingrediente) {
                DB::table('ingrediente_producto')->insert([
                    'producto_id' => $producto->id,
                    'ingrediente_id' => $ingrediente->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
