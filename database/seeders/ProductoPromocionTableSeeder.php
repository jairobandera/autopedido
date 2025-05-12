<?php

// database/seeders/ProductoPromocionTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductoPromocionTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        DB::table('producto_promocion')->insert([
            ['producto_id' => 1, 'promocion_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['producto_id' => 2, 'promocion_id' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['producto_id' => 3, 'promocion_id' => 3, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}

