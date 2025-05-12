<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use App\Models\Promocion;

class ProductoPromocionSeeder extends Seeder
{
    public function run()
    {
        $productos = Producto::all();
        $promociones = Promocion::all();

        // Si no hay productos o promociones, salimos sin hacer nada
        if ($productos->isEmpty() || $promociones->isEmpty()) {
            return;
        }

        foreach ($productos as $producto) {
            // Asigna entre 1 y 3 promociones (como máximo las que existan)
            $max = min(3, $promociones->count());
            $seleccionadas = $promociones->random(rand(1, $max));

            foreach ($seleccionadas as $promo) {
                DB::table('producto_promocion')->insert([
                    'producto_id' => $producto->id,
                    'promocion_id' => $promo->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
