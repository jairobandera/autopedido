<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use Illuminate\Support\Str;

class ClienteSeeder extends Seeder
{
    public function run()
    {
        // Puedes tener un array de nombres/apellidos o generarlos con Faker
        // Aquí usamos Faker para generar datos realistas:
        $faker = \Faker\Factory::create('es_ES');

        for ($i = 1; $i <= 10; $i++) {
            $cedula = str_pad((string) rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            $telefono = '09' . rand(10000000, 99999999);

            Cliente::create([
                'nombre' => $faker->firstName(),
                'apellido' => $faker->lastName(),
                'cedula' => $cedula,
                'telefono' => $telefono,
                'puntos' => rand(0, 500),
                'activo' => (bool) random_int(0, 1),
            ]);
        }
    }
}
