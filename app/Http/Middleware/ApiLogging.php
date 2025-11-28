<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ApiLogging
{
    /**
     * Handle an incoming request.
     *
     * Registra cada petición API con información relevante para auditoría y debugging.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        // Información de la petición
        $requestData = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => $request->user() ? $request->user()->id : null,
            'timestamp' => now()->toDateTimeString(),
        ];

        // Agregar parámetros de la petición (excepto contraseñas y tokens sensibles)
        $input = $request->except(['password', 'password_confirmation', 'token', 'api_token']);
        if (!empty($input)) {
            $requestData['input'] = $input;
        }

        // Procesar la petición
        $response = $next($request);

        // Calcular tiempo de ejecución
        $executionTime = round((microtime(true) - $startTime) * 1000, 2); // en milisegundos

        // Información de la respuesta
        $responseData = [
            'status_code' => $response->getStatusCode(),
            'execution_time_ms' => $executionTime,
        ];

        // Combinar datos de petición y respuesta
        $logData = array_merge($requestData, $responseData);

        // Determinar el nivel de log según el código de estado
        $logLevel = 'info';
        if ($response->getStatusCode() >= 500) {
            $logLevel = 'error';
        } elseif ($response->getStatusCode() >= 400) {
            $logLevel = 'warning';
        }

        // Registrar el log
        Log::channel('daily')->$logLevel('API Request', $logData);

        // Opcional: También registrar en el log por defecto
        // Log::$logLevel('API Request', $logData);

        return $response;
    }
}

