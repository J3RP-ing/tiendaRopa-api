<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PersonalizacionController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\DetalleCarritoController;
use App\Http\Controllers\PedidoController;
use Illuminate\Support\Facades\Route;


Route::apiResource('usuarios', UsuarioController::class);
Route::apiResource('categorias', CategoriaController::class);
Route::apiResource('productos', ProductoController::class);
Route::apiResource('personalizaciones', PersonalizacionController::class);

Route::apiResource('carritos', CarritoController::class); //ver si es carritos o carrito
Route::apiResource('detalles_carrito', DetalleCarritoController::class);// ver si es detalles_
Route::apiResource('pedidos', PedidoController::class); //ver si es pedidos o pedido

//esto es lo que aparecio con el forced
Route::apiResource('carritos', CarritoController::class);
Route::apiResource('detalles_carrito', DetalleCarritoController::class);
Route::post('/login', [AuthController::class, 'login']);

