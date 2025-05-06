<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        // Si el usuario está autenticado, redirige al dashboard correspondiente
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->rol === 'Administrador') {
                return redirect()->route('Administrador.dashboard');
            } elseif ($user->rol === 'Cajero') {
                return redirect()->route('Caja.dashboard');
            } elseif ($user->rol === 'Cocina') {
                return redirect()->route('Cocina.dashboard');
            }
        }

        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'contrasena' => 'required|string',
        ]);

        // Busca al usuario por 'nombre'
        $user = User::where('nombre', $request->nombre)->first();

        // Verifica que el usuario exista y la contraseña sea correcta
        if (!$user || !Hash::check($request->contrasena, $user->contrasena)) {
            return back()->withErrors([
                'nombre' => 'Las credenciales no coinciden con nuestros registros.',
            ]);
        }

        // Inicia sesión
        Auth::login($user);

        // Redirige según el rol del usuario
        if ($user->rol === 'Administrador') {
            return redirect()->route('Administrador.dashboard'); // Redirige al dashboard del administrador
        } elseif ($user->rol === 'Cajero') {
            return redirect()->route('Caja.dashboard'); // Redirige al dashboard de la caja
        } elseif ($user->rol === 'Cocina') {
            return redirect()->route('Cocina.dashboard'); // Redirige al dashboard de la cocina
        } else {
            return redirect()->route('login')->withErrors(['rol' => 'Rol no autorizado']); // Si el rol no es reconocido
        }
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}


