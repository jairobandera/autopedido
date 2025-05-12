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
            CategoriaSeeder::class,
            IngredienteSeeder::class,
            ProductoSeeder::class,
            PromocionSeeder::class,
            CategoriaProductoSeeder::class,
            IngredienteProductoSeeder::class,
            ProductoPromocionSeeder::class,
            PedidoSeeder::class,
            PagoSeeder::class,
            PuntoPedidoSeeder::class,
            DetallePedidoSeeder::class,
        ]);
    }
}
