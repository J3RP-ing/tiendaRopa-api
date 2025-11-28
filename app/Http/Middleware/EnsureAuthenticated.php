<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\Sanctum;

class EnsureAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * Verifica que el usuario esté autenticado mediante un token válido de Sanctum.
     * 
     * El token debe enviarse en el header: Authorization: Bearer {token}
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si hay un token en la petición
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'message' => 'No autenticado. Token requerido en el header Authorization: Bearer {token}',
                'error' => 'Unauthenticated'
            ], 401);
        }

        // Verificar si el usuario está autenticado mediante Sanctum
        if (!Sanctum::check($request)) {
            return response()->json([
                'message' => 'Token inválido o expirado.',
                'error' => 'Unauthenticated'
            ], 401);
        }

        // Obtener el usuario autenticado
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'message' => 'No se pudo autenticar el usuario.',
                'error' => 'Unauthenticated'
            ], 401);
        }

        // Verificar que el token no haya expirado (si tiene expiración)
        $accessToken = $user->currentAccessToken();
        if ($accessToken && $accessToken->expires_at && $accessToken->expires_at->isPast()) {
            return response()->json([
                'message' => 'Token expirado. Por favor, inicia sesión nuevamente.',
                'error' => 'Token expired'
            ], 401);
        }

        return $next($request);
    }
}

