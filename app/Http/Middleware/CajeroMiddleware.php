<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CajeroMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //verifica si el usuario está autenticado y si tiene el rol de 'Cajero'
        if (auth()->check() && auth()->user()->rol === 'Cajero') {
            return $next($request);
        }

        //si no es cajero, redirige al dashboard correspondiente o a la página de inicio
        return redirect('/');
    }
}