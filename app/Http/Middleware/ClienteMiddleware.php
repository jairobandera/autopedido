<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClienteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //verifica si el usuario está autenticado y si tiene el rol de 'Cliente'
        if (auth()->check() && auth()->user()->rol === 'Cliente') {
            return $next($request);
        }

        //si no es administrador, redirige al usuario a la página de inicio
        return redirect('/');
    }
}