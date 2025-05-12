<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsuarioSeeder::class,
            ClienteSeeder::class,
            ProductoSeeder::class,
            CategoriaSeeder::class,
            IngredienteSeeder::class,
            PromocionesTableSeeder::class,
            ProductoPromocionTableSeeder::class,
            PedidoSeeder::class,
            DetallePedidoSeeder::class,
            PagoSeeder::class,
            PuntoPedidoSeeder::class,
            IngredienteProductoSeeder::class,
            CategoriaProductoSeeder::class,
        ]);
    }
}