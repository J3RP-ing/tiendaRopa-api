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

// Ruta pública de login (sin autenticación)
Route::post('/login', [AuthController::class, 'login']);

// Rutas públicas (sin autenticación)
Route::apiResource('productos', ProductoController::class);
Route::apiResource('categorias', CategoriaController::class);

// Rutas que requieren autenticación
Route::middleware(['auth.api', 'api.logging'])->group(function () {
    Route::apiResource('carritos', CarritoController::class);
    Route::apiResource('detalles_carrito', DetalleCarritoController::class);
    Route::apiResource('detalles_pedido', DetallePedidoController::class);
    Route::apiResource('pagos', PagoController::class);
    Route::apiResource('personalizaciones', PersonalizacionController::class);

    Route::middleware('ownership:carrito')->group(function () {
        Route::get('/carritos/{carrito}', [CarritoController::class, 'show']);
        Route::put('/carritos/{carrito}', [CarritoController::class, 'update']);
        Route::delete('/carritos/{carrito}', [CarritoController::class, 'destroy']);
    });
});

// Rutas solo para administradores
Route::middleware(['auth.api', 'role:admin', 'api.logging'])->group(function () {
    Route::apiResource('usuarios', UsuarioController::class);
});
