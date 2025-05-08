<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CocinaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //verifica si el usuario está autenticado y si tiene el rol de 'Cocina'
        if (auth()->check() && auth()->user()->rol === 'Cocina') {
            return $next($request);
        }

        //si no es cocina, redirige al dashboard correspondiente o a la página de inicio
        return redirect('/');
    }
}