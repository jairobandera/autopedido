<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetallePedido;
use App\Models\Ingrediente;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class DetallePedidoIngredienteQuitadoSeeder extends Seeder
{
    public function run()
    {
        $detalles = DetallePedido::all();
        $productos = Producto::all()->keyBy('id');
        $ingredientesProducto = DB::table('ingrediente_producto')
            ->where('es_obligatorio', false)
            ->get()
            ->groupBy('producto_id');

        foreach ($detalles as $detalle) {
            if (isset($productos[$detalle->producto_id]) && isset($ingredientesProducto[$detalle->producto_id])) {
                $ingredientesDisponibles = $ingredientesProducto[$detalle->producto_id]
                    ->pluck('ingrediente_id')
                    ->toArray();

                // Elegir hasta 2 ingredientes no obligatorios para quitar
                $quitados = collect($ingredientesDisponibles)
                    ->shuffle()
                    ->take(rand(0, 2))
                    ->toArray();

                if (!empty($quitados)) {
                    $detalle->ingredientesQuitados()->attach($quitados);
                }
            }
        }
    }
}