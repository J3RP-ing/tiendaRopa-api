<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PersonalizacionController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\DetalleCarritoController;
use App\Http\Controllers\DetallePedidoController;
use App\Http\Controllers\PagoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Middlewares Disponibles
|--------------------------------------------------------------------------
|
| - auth.api: Verifica autenticación mediante token Sanctum
| - role:admin,cliente: Verifica que el usuario tenga uno de los roles especificados
| - ownership:carrito|pedido: Verifica que el recurso pertenezca al usuario autenticado
| - api.logging: Registra todas las peticiones API para auditoría
|
| Ejemplos de uso:
| Route::get('/ruta', [Controller::class, 'method'])->middleware('auth.api');
| Route::get('/ruta', [Controller::class, 'method'])->middleware('role:admin');
| Route::get('/ruta', [Controller::class, 'method'])->middleware('role:admin,cliente');
| Route::get('/carritos/{carrito}', [Controller::class, 'method'])->middleware('ownership:carrito');
| Route::get('/ruta', [Controller::class, 'method'])->middleware(['auth.api', 'role:admin', 'api.logging']);
|
*/

// Ruta pública de login (sin autenticación)
Route::post('/login', [AuthController::class, 'login']);

// Rutas públicas (sin autenticación)
Route::apiResource('productos', ProductoController::class);
Route::apiResource('categorias', CategoriaController::class);

// Rutas que requieren autenticación
Route::middleware(['auth.api', 'api.logging'])->group(function () {
    // Rutas de usuario autenticado
    Route::apiResource('carritos', CarritoController::class);
    Route::apiResource('detalles_carrito', DetalleCarritoController::class);
    Route::apiResource('detalles_pedido', DetallePedidoController::class);
    Route::apiResource('pagos', PagoController::class);
    Route::apiResource('personalizaciones', PersonalizacionController::class);

    // Ejemplo: Rutas con verificación de propiedad
    // Estas rutas verifican que el carrito/pedido pertenezca al usuario autenticado
    Route::middleware('ownership:carrito')->group(function () {
        Route::get('/carritos/{carrito}', [CarritoController::class, 'show']);
        Route::put('/carritos/{carrito}', [CarritoController::class, 'update']);
        Route::delete('/carritos/{carrito}', [CarritoController::class, 'destroy']);
    });
});

// Rutas solo para administradores
Route::middleware(['auth.api', 'role:admin', 'api.logging'])->group(function () {
    Route::apiResource('usuarios', UsuarioController::class);

    // Ejemplo: Ruta específica solo para admin
    // Route::get('/admin/estadisticas', [AdminController::class, 'estadisticas']);
});

// Ejemplo de ruta con múltiples middlewares
// Route::get('/admin/carritos/{carrito}', [AdminController::class, 'verCarrito'])
//     ->middleware(['auth.api', 'role:admin', 'ownership:carrito', 'api.logging']);
