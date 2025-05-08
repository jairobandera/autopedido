<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //verifica si el usuario está autenticado y si tiene el rol de 'Administrador'
        if (auth()->check() && auth()->user()->rol === 'Administrador') {
            return $next($request);
        }

        //si no es administrador, redirige al usuario a la página de inicio
        return redirect('/');
    }
}
