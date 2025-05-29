<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Usuario;

class ClienteSeeder extends Seeder
{
    public function run()
    {
        $clientes = Usuario::where('rol', 'Cliente')->get();

        if ($clientes->isEmpty()) {
            return;
        }

        foreach ($clientes as $usuario) {
            Cliente::create([
                'usuario_id' => $usuario->id,
                'puntos_totales' => rand(0, 500), // Puntos acumulados realistas
            ]);
        }
    }
}