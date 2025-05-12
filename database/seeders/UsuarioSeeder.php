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
            // Mantener los 5 usuarios originales sin cambios
            ['nombre' => 'Cliente1', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cliente', 'activo' => true],
            ['nombre' => 'Cliente2', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cliente', 'activo' => true],
            ['nombre' => 'Admin', 'contrasena' => bcrypt('123456789'), 'rol' => 'Administrador', 'activo' => true],
            ['nombre' => 'Cajero1', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cajero', 'activo' => true],
            ['nombre' => 'Cajero2', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cajero', 'activo' => true],

            // 15 usuarios adicionales con datos realistas en español
            ['nombre' => 'Juan Pérez', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cliente', 'activo' => true],
            ['nombre' => 'María González', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cliente', 'activo' => true],
            ['nombre' => 'Carlos López', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cliente', 'activo' => true],
            ['nombre' => 'Ana Martínez', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cliente', 'activo' => true],
            ['nombre' => 'Lucía Fernández', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cliente', 'activo' => true],
            ['nombre' => 'Diego Rodríguez', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cliente', 'activo' => true],
            ['nombre' => 'Sofía Sánchez', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cliente', 'activo' => true],
            ['nombre' => 'Pedro Gómez', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cajero', 'activo' => true],
            ['nombre' => 'Laura Díaz', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cajero', 'activo' => true],
            ['nombre' => 'Miguel Torres', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cajero', 'activo' => true],
            ['nombre' => 'Elena Ramírez', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cocina', 'activo' => true],
            ['nombre' => 'Javier Morales', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cocina', 'activo' => true],
            ['nombre' => 'Carmen Ruiz', 'contrasena' => bcrypt('123456789'), 'rol' => 'Cocina', 'activo' => true],
            ['nombre' => 'Alan Ceballos', 'contrasena' => bcrypt('123456789'), 'rol' => 'Administrador', 'activo' => true],
            ['nombre' => 'Jairo Bandera', 'contrasena' => bcrypt('123456789'), 'rol' => 'Administrador', 'activo' => true],
        ];

        foreach ($usuarios as $usuario) {
            Usuario::create($usuario);
        }
    }
}