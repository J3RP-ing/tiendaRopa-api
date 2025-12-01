<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validamos los campos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Tomamos las credenciales
        $credentials = $request->only('email', 'password');

        // Verificamos si son correctas
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // Si son correctas, traemos el usuario
        $user = Auth::user();

        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user
        ]);
    }
}
