<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Mostrar formulario de registro
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');  // Redirige al dashboard si ya está autenticado
        }

        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');  // Redirige al dashboard si ya está autenticado
        }

        return view('auth.register');
    }

    // Registrar al usuario
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        Auth::login($user);

        // Enviar correo de confirmación
        // ...

        return redirect()->route('dashboard');
    }

 

    // Iniciar sesión
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Redirige al dashboard o a la página que necesites después de iniciar sesión
            return redirect()->intended('/dashboard');
        }

        // Si la autenticación falla, redirige de nuevo a la página de login con un mensaje de error
        return redirect()->route('login')->withErrors(['email' => 'Credenciales no válidas']);
    }


    // Cerrar sesión
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}

