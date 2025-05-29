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
    public function run(): void
    {
        $usuarios = [
            ['nombre' => 'Cliente1', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cliente', 'activo' => true],
            ['nombre' => 'Cliente2', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cliente', 'activo' => true],
            ['nombre' => 'Admin', 'contrasena' => bcrypt('123456789'), 'rol' => 'Administrador', 'activo' => true],
            ['nombre' => 'Cajero1', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cajero', 'activo' => true],
            ['nombre' => 'Cajero2', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cajero', 'activo' => true],
            ['nombre' => 'Cocina1', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cocina', 'activo' => true],
            ['nombre' => 'Cocina2', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cocina', 'activo' => true],
        ];

        foreach ($usuarios as $usuario) {
            Usuario::create($usuario);
        }
    }
}