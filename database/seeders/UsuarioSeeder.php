<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $usuarios = [
            ['nombre' => 'juan.perez', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cliente', 'activo' => true],
            ['nombre' => 'maria.rodriguez', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cajero', 'activo' => true],
            ['nombre' => 'admin', 'contrasena' => bcrypt('123456789'), 'rol' => 'Administrador', 'activo' => true],
        ];

        foreach ($usuarios as $usuario) {
            Usuario::create($usuario);
        }
    }
}
