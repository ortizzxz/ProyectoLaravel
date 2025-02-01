<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user() && auth()->user()->es_admin) {
            return $next($request);
        }

        return redirect('/')->with('error', 'No tienes acceso a esta área.');
    }
}
