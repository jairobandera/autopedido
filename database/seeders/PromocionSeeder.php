<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromocionSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $promos = [
            [
                'nombre' => 'Martes 2x1 Hamburguesas',
                'descuento' => 50.00,
                'codigo' => 'MARTES21',
                'fecha_inicio' => $now->toDateString(),
                'fecha_fin' => $now->copy()->addWeek()->toDateString(),
                'activo' => true,
            ],
            [
                'nombre' => 'Happy Hour Bebidas',
                'descuento' => 30.00,
                'codigo' => 'HAPPYHOUR',
                'fecha_inicio' => $now->toDateString(),
                'fecha_fin' => $now->copy()->addDays(3)->toDateString(),
                'activo' => true,
            ],
            [
                'nombre' => 'Combo Familiar',
                'descuento' => 25.00,
                'codigo' => null,
                'fecha_inicio' => null,
                'fecha_fin' => null,
                'activo' => true,
            ],
            [
                'nombre' => 'Lunes Saludable',
                'descuento' => 20.00,
                'codigo' => 'SALUD20',
                'fecha_inicio' => $now->toDateString(),
                'fecha_fin' => $now->copy()->addDays(7)->toDateString(),
                'activo' => true,
            ],
        ];

        foreach ($promos as $p) {
            DB::table('promociones')->insert(array_merge($p, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
