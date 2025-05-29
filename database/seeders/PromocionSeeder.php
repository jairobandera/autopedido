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
                'nombre' => 'Martes 2x1 en Pizzas',
                'descuento' => 50.00, // 50% de descuento
                'codigo' => 'PIZZA2X1',
                'fecha_inicio' => $now->toDateString(),
                'fecha_fin' => $now->copy()->addWeek()->toDateString(),
                'activo' => true,
            ],
            [
                'nombre' => 'Happy Hour Bebidas',
                'descuento' => 30.00, // 30% de descuento
                'codigo' => 'HAPPY30',
                'fecha_inicio' => $now->toDateString(),
                'fecha_fin' => $now->copy()->addDays(7)->toDateString(),
                'activo' => true,
            ],
            [
                'nombre' => 'Combo Familiar',
                'descuento' => 300.00, // Descuento fijo de $300
                'codigo' => null,
                'fecha_inicio' => null,
                'fecha_fin' => null,
                'activo' => true,
            ],
            [
                'nombre' => 'Lunes Saludable',
                'descuento' => 20.00, // 20% de descuento
                'codigo' => 'SALUD20',
                'fecha_inicio' => $now->startOfWeek()->toDateString(),
                'fecha_fin' => $now->copy()->addDays(7)->toDateString(),
                'activo' => true,
            ],
            [
                'nombre' => 'Jueves de Empanadas',
                'descuento' => 100.00, // Descuento fijo de $100
                'codigo' => 'EMPANADA100',
                'fecha_inicio' => $now->copy()->next(Carbon::THURSDAY)->toDateString(),
                'fecha_fin' => $now->copy()->next(Carbon::THURSDAY)->addDays(7)->toDateString(),
                'activo' => true,
            ],
            [
                'nombre' => 'Fin de Semana Dulce',
                'descuento' => 25.00, // 25% de descuento
                'codigo' => 'DULCE25',
                'fecha_inicio' => $now->copy()->next(Carbon::FRIDAY)->toDateString(),
                'fecha_fin' => $now->copy()->next(Carbon::SUNDAY)->toDateString(),
                'activo' => true,
            ],
            [
                'nombre' => 'Día del Niño',
                'descuento' => 20.00, // 20% de descuento
                'codigo' => 'NINO20',
                'fecha_inicio' => Carbon::create($now->year, 8, 15)->toDateString(),
                'fecha_fin' => Carbon::create($now->year, 8, 22)->toDateString(),
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