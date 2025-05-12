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
        $productos = Producto::all();
        $ingredientes = Ingrediente::all();

        // Si no hay productos o ingredientes, salimos
        if ($productos->isEmpty() || $ingredientes->isEmpty()) {
            return;
        }

        foreach ($productos as $producto) {
            // Asigna entre 2 y 5 ingredientes (o menos si no hay tantos)
            $max = min(5, $ingredientes->count());
            $cantidad = rand(2, $max);
            $seleccionados = $ingredientes->random($cantidad);

            foreach ($seleccionados as $ingrediente) {
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
