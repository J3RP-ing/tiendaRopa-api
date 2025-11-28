<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Verifica que el usuario autenticado tenga uno de los roles permitidos.
     * 
     * Uso: ->middleware('role:admin') o ->middleware('role:admin,cliente')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificar que el usuario esté autenticado
        if (!$request->user()) {
            return response()->json([
                'message' => 'No autenticado. Token requerido.',
                'error' => 'Unauthenticated'
            ], 401);
        }

        // Obtener el usuario autenticado
        $user = $request->user();

        // Si el usuario es de tipo User (Sanctum), necesitamos obtener el Usuario relacionado
        // Asumiendo que User y Usuario están relacionados o son el mismo
        $usuario = null;
        
        // Intentar obtener el rol del usuario
        // Si User tiene relación con Usuario, obtenerlo
        if (method_exists($user, 'usuario')) {
            $usuario = $user->usuario;
        } elseif (isset($user->rol)) {
            // Si User tiene directamente el campo rol
            $rol = $user->rol;
        } else {
            // Buscar en la tabla de usuarios por correo
            $usuario = \App\Models\Usuario::where('correo', $user->email)->first();
        }

        // Obtener el rol
        $userRole = $usuario ? $usuario->rol : ($user->rol ?? null);

        if (!$userRole) {
            return response()->json([
                'message' => 'Usuario sin rol asignado.',
                'error' => 'No role assigned'
            ], 403);
        }

        // Verificar si el rol del usuario está en la lista de roles permitidos
        if (!in_array($userRole, $roles)) {
            return response()->json([
                'message' => 'Acceso denegado. No tienes los permisos necesarios.',
                'error' => 'Forbidden',
                'required_roles' => $roles,
                'user_role' => $userRole
            ], 403);
        }

        return $next($request);
    }
}

