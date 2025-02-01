<?php
    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    
    class AdminMiddleware
    {
        public function handle(Request $request, Closure $next)
        {
            // Verificar si el usuario es administrador, puedes tener una columna `is_admin` en tu tabla de usuarios
            if (!auth()->check() || !auth()->user()->is_admin) {
                return redirect()->route('home'); // Redirige si no es admin
            }
    
            return $next($request);
        }
    }
    