<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PersonalizacionController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\DetalleCarritoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\DetallePedidoController;
use App\Http\Controllers\PagoController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('productos', ProductoController::class);
Route::apiResource('categorias', CategoriaController::class);

/*
|--------------------------------------------------------------------------
| Rutas protegidas (usuarios autenticados)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth.api', 'api.logging'])->group(function () {
    Route::apiResource('carritos', CarritoController::class);
    Route::apiResource('detalles_carrito', DetalleCarritoController::class);
    Route::apiResource('pedidos', PedidoController::class);
    Route::apiResource('detalles_pedido', DetallePedidoController::class);
    Route::apiResource('pagos', PagoController::class);
    Route::apiResource('personalizaciones', PersonalizacionController::class);

    // Ownership de carrito
    Route::middleware('ownership:carrito')->group(function () {
        Route::get('/carritos/{carrito}', [CarritoController::class, 'show']);
        Route::put('/carritos/{carrito}', [CarritoController::class, 'update']);
        Route::delete('/carritos/{carrito}', [CarritoController::class, 'destroy']);
    });
});

/*
|--------------------------------------------------------------------------
| Rutas solo para administradores
|--------------------------------------------------------------------------
*/

Route::middleware(['auth.api', 'role:admin', 'api.logging'])->group(function () {
    Route::apiResource('usuarios', UsuarioController::class);
});
