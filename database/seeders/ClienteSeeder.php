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

        // Validar que existan usuarios con rol 'Cliente'
        if ($usuariosCliente->isEmpty()) {
            return; // No crea registros si no hay usuarios con rol 'Cliente'
        }

        foreach ($usuariosCliente as $usuario) {
            Cliente::create([
                'usuario_id' => $usuario->id,
                'puntos_totales' => $this->generarPuntos($usuario->nombre),
            ]);
        }
    }

    private function generarPuntos($nombre)
    {
        $puntosPorUsuario = [
            'Cliente1' => 200, // Cliente de prueba con compras moderadas
            'Cliente2' => 350, // Cliente de prueba con más compras
            'Juan Pérez' => 150, // Cliente nuevo
            'María González' => 500, // Cliente frecuente
            'Carlos López' => 300, // Cliente regular
            'Ana Martínez' => 100, // Cliente ocasional
            'Lucía Fernández' => 450, // Cliente frecuente
            'Diego Rodríguez' => 250, // Cliente regular
            'Sofía Sánchez' => 50, // Cliente muy nuevo
        ];

        return $puntosPorUsuario[$nombre] ?? rand(0, 100);
    }
}