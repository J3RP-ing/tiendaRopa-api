<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestPedido;
use App\Services\PedidoService;
use Illuminate\Http\JsonResponse;

class PedidoController extends Controller
{
    protected $pedidoService;

    public function __construct(PedidoService $pedidoService)
    {
        $this->pedidoService = new $pedidoService;
    }

    public function index(): JsonResponse
    {
        try {
            $pedidos = $this->pedidoService->obtenerPedidos();
            return response()->json(['data' => $pedidos], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los pedidos',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(RequestPedido $request): JsonResponse
    {
        try {
            $pedido = $this->pedidoService->crearPedido($request->validated());

            return response()->json([
                'message' => 'Pedido creado exitosamente',
                'data' => $pedido,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el pedido',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $pedido = $this->pedidoService->obtenerPedidoPorId((int) $id);

            if (! $pedido) {
                return response()->json(['error' => 'Pedido no encontrado'], 404);
            }

            return response()->json(['data' => $pedido], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el pedido',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(RequestPedido $request, string $id): JsonResponse
    {
        try {
            $pedido = $this->pedidoService->actualizarPedido($id, $request->validated());

            if (! $pedido) {
                return response()->json(['error' => 'Pedido no encontrado'], 404);
            }

            return response()->json([
                'message' => 'Pedido actualizado exitosamente',
                'data' => $pedido,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el pedido',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $eliminado = $this->pedidoService->eliminarPedido((int) $id);

            if (! $eliminado) {
                return response()->json(['error' => 'Pedido no encontrado'], 404);
            }

            return response()->json(['message' => 'Pedido eliminado exitosamente'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el pedido',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function restore(string $id): JsonResponse
    {
        try {
            $restaurado = $this->pedidoService->restaurarPedido((int) $id);

            if (! $restaurado) {
                return response()->json(['error' => 'Pedido no encontrado o no está eliminado'], 404);
            }

            return response()->json(['message' => 'Pedido restaurado exitosamente'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al restaurar el pedido',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
