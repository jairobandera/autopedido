<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromocionesTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('promociones')->insert([
            // Promoción 1: Descuento permanente en combos
            [
                'nombre' => 'Descuento en Combos',
                'descuento' => 20.00, // 20% de descuento
                'codigo' => null,
                'fecha_inicio' => null,
                'fecha_fin' => null,
                'activo' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Promoción 2: 2x1 en empanadas los fines de semana
            [
                'nombre' => '2x1 en Empanadas',
                'descuento' => 50.00, // 50% (equivalente a 2x1)
                'codigo' => 'EMPANADAS2X1',
                'fecha_inicio' => Carbon::create(2025, 1, 1)->toDateString(),
                'fecha_fin' => Carbon::create(2025, 12, 31)->toDateString(),
                'activo' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Promoción 3: Descuento en pizzas para el verano
            [
                'nombre' => 'Verano de Pizzas',
                'descuento' => 15.00, // 15% de descuento
                'codigo' => 'PIZZAVERANO',
                'fecha_inicio' => Carbon::create(2025, 6, 1)->toDateString(),
                'fecha_fin' => Carbon::create(2025, 8, 31)->toDateString(),
                'activo' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Promoción 4: Descuento por Black Friday
            [
                'nombre' => 'Black Friday Gastronómico',
                'descuento' => 25.00, // 25% de descuento
                'codigo' => null,
                'fecha_inicio' => Carbon::create(2025, 11, 28)->toDateString(),
                'fecha_fin' => Carbon::create(2025, 11, 30)->toDateString(),
                'activo' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Promoción 5: Descuento en postres para cumpleaños
            [
                'nombre' => 'Postre de Cumpleaños',
                'descuento' => 30.00, // 30% de descuento
                'codigo' => 'CUMPLE30',
                'fecha_inicio' => null,
                'fecha_fin' => null,
                'activo' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}