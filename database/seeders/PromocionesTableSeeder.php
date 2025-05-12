<?php

// database/seeders/PromocionesTableSeeder.php
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
            [
                'nombre' => 'Combo 2x1',
                'descuento' => 50.00,
                'codigo' => null,
                'fecha_inicio' => null,
                'fecha_fin' => null,
                'activo' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Cajita Feliz',
                'descuento' => 25.00,
                'codigo' => 'CAJITA25',
                'fecha_inicio' => $now->subDays(5)->toDateString(),
                'fecha_fin' => $now->addDays(10)->toDateString(),
                'activo' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nombre' => 'Día de la Madre',
                'descuento' => 30.00,
                'codigo' => null,
                'fecha_inicio' => Carbon::create($now->year, 5, 10)->toDateString(),
                'fecha_fin' => Carbon::create($now->year, 5, 18)->toDateString(),
                'activo' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
