<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Usuario;

class ClienteSeeder extends Seeder
{
    public function run()
    {
        $usuario = Usuario::where('rol', 'Cliente')->first();
        if ($usuario) {
            Cliente::create([
                'usuario_id' => $usuario->id,
                'puntos_totales' => rand(0, 200),
            ]);
        }
    }
}
