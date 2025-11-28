<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Carrito;

class EnsureOwnership
{
    /**
     * Handle an incoming request.
     *
     * Verifica que el recurso (carrito/pedido) pertenezca al usuario autenticado.
     * 
     * Uso: ->middleware('ownership:carrito') o ->middleware('ownership:pedido')
     * 
     * El parámetro indica el tipo de recurso a verificar.
     * El ID del recurso debe venir en la ruta (ej: /carritos/{carrito})
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $resourceType = 'carrito'): Response
    {
        // Verificar que el usuario esté autenticado
        if (!$request->user()) {
            return response()->json([
                'message' => 'No autenticado. Token requerido.',
                'error' => 'Unauthenticated'
            ], 401);
        }

        $user = $request->user();
        
        // Obtener el ID del usuario autenticado
        // Si User tiene relación con Usuario, obtener el id_usuario
        $userId = null;
        
        if (method_exists($user, 'usuario')) {
            $usuario = $user->usuario;
            $userId = $usuario ? $usuario->id_usuario : null;
        } else {
            // Buscar el usuario por correo
            $usuario = \App\Models\Usuario::where('correo', $user->email)->first();
            $userId = $usuario ? $usuario->id_usuario : null;
        }

        if (!$userId) {
            return response()->json([
                'message' => 'No se pudo identificar el usuario.',
                'error' => 'User not found'
            ], 404);
        }

        // Obtener el ID del recurso desde la ruta
        $resourceId = null;
        
        // Intentar obtener el ID desde diferentes nombres de parámetros comunes
        $possibleParamNames = [
            $resourceType, // carrito, pedido
            'id',
            'id_' . $resourceType, // id_carrito, id_pedido
            'carrito',
            'pedido'
        ];

        foreach ($possibleParamNames as $paramName) {
            if ($request->route($paramName)) {
                $resourceId = $request->route($paramName);
                break;
            }
        }

        if (!$resourceId) {
            return response()->json([
                'message' => 'No se pudo identificar el recurso a verificar.',
                'error' => 'Resource ID not found'
            ], 400);
        }

        // Verificar la propiedad según el tipo de recurso
        $isOwner = false;

        switch (strtolower($resourceType)) {
            case 'carrito':
                $carrito = Carrito::find($resourceId);
                if ($carrito && $carrito->id_usuario == $userId) {
                    $isOwner = true;
                }
                break;

            case 'pedido':
                // Buscar el pedido (aunque el modelo fue eliminado, podemos usar DB directamente)
                $pedido = \Illuminate\Support\Facades\DB::table('tblPedidos')
                    ->where('id_pedido', $resourceId)
                    ->where('id_usuario', $userId)
                    ->first();
                $isOwner = (bool) $pedido;
                break;

            default:
                return response()->json([
                    'message' => 'Tipo de recurso no soportado.',
                    'error' => 'Unsupported resource type',
                    'supported_types' => ['carrito', 'pedido']
                ], 400);
        }

        if (!$isOwner) {
            return response()->json([
                'message' => 'No tienes permiso para acceder a este recurso.',
                'error' => 'Forbidden',
                'resource_type' => $resourceType,
                'resource_id' => $resourceId
            ], 403);
        }

        return $next($request);
    }
}

