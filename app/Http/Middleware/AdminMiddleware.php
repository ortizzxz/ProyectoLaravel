<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica si el usuario está autenticado y si es administrador
        if (auth()->check() && auth()->user()->es_admin) {
            return $next($request);
        }

        // Si no es administrador, redirige
        return redirect('/')->with('error', 'No tienes acceso a esta área.');
    }
}
