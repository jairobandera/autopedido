<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Usuario;

class ClienteSeeder extends Seeder
{
    public function run()
    {
        // Buscar usuarios con rol 'Cliente'
        $usuariosCliente = Usuario::where('rol', 'Cliente')->get();

        foreach ($usuariosCliente as $usuario) {
            Cliente::create([
                'usuario_id' => $usuario->id,
                'puntos_totales' => rand(0, 500),
            ]);
        }
    }
}
