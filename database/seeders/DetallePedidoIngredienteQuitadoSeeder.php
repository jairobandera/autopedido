<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetallePedido;
use App\Models\Ingrediente;

class DetallePedidoIngredienteQuitadoSeeder extends Seeder
{
    public function run()
    {
        // Obtiene todos los detalles y todos los ingredientes
        $detalles = DetallePedido::all();
        $ingredientes = Ingrediente::all()->pluck('id')->toArray();

        foreach ($detalles as $detalle) {
            // Para cada detalle, elige al azar hasta 2 ingredientes distintos a quitar
            $quitados = collect($ingredientes)
                ->shuffle()
                ->take(rand(0, 2))
                ->toArray();

            if (count($quitados)) {
                // Adjunta los quitados
                $detalle->ingredientesQuitados()->attach($quitados);
            }
        }
    }
}