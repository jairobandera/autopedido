<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function mostrarFormulario()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nombre_usuario' => 'required|string',
            'password' => 'required|string'
        ]);

        // Buscar usuario por nombre
        $usuario = Usuario::where('nombre', $request->nombre_usuario)->first();

        // Verificar si existe y si la contraseña coincide
        if (!$usuario || !Hash::check($request->password, $usuario->contrasena)) {
            return redirect()->route('login')->with('error', 'Error en el nombre de usuario o contraseña.');
        }

        // Guardar datos en sesión
        session(['usuario_id' => $usuario->id, 'rol' => $usuario->rol]);

        // Redirección por rol
        switch (strtolower($usuario->rol)) {
            case 'administrador':
                return redirect()->route('Administrador.dashboard');
            case 'cajero':
                return redirect()->route('Caja.dashboard');
            case 'cocina':
                return redirect()->route('Cocina.dashboard');
            default:
                return redirect('/');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login')->with('logout_success', true);
    }

}
