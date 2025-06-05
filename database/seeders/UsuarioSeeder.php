<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            // Ya no incluimos 'rol' => 'Cliente'
            ['nombre' => 'Admin',    'contrasena' => bcrypt('123456789'), 'rol' => 'Administrador', 'activo' => true],
            ['nombre' => 'Cajero1',  'contrasena' => bcrypt('123456789'), 'rol' => 'Cajero',        'activo' => true],
            ['nombre' => 'Cajero2',  'contrasena' => bcrypt('123456789'), 'rol' => 'Cajero',        'activo' => true],
            ['nombre' => 'Cocina1',  'contrasena' => bcrypt('123456789'), 'rol' => 'Cocina',        'activo' => true],
            ['nombre' => 'Cocina2',  'contrasena' => bcrypt('123456789'), 'rol' => 'Cocina',        'activo' => true],
            // Si necesitas un administrador extra o un rol distinto, agrégalo aquí:
            // ['nombre' => 'Supervisor', 'contrasena' => bcrypt('123456789'), 'rol' => 'Administrador', 'activo' => true],
        ];

        foreach ($usuarios as $u) {
            Usuario::create($u);
        }
    }
}
