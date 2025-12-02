<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestDetallePedido;
use App\Services\DetallePedidoService;
use Illuminate\Http\JsonResponse;

class DetallePedidoController extends Controller
{
    protected $detalleService;

    public function __construct(DetallePedidoService $detalleService)
    {
        $this->detalleService = new $detalleService;
    }

    public function index(): JsonResponse
    {
        try {
            $detalles = $this->detalleService->obtenerDetalles();
            return response()->json(['data' => $detalles], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los detalles',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(RequestDetallePedido $request): JsonResponse
    {
        try {
            $detalle = $this->detalleService->crearDetalle($request->validated());

            if (!$detalle) {
                return response()->json([
                    'error' => 'Error al crear el detalle. Verifique que el producto existe.'
                ], 400);
            }

            return response()->json([
                'message' => 'Detalle agregado correctamente',
                'data' => $detalle,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al agregar detalle',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $detalle = $this->detalleService->obtenerDetallePorId((int)$id);

            if (!$detalle) {
                return response()->json(['error' => 'Detalle no encontrado'], 404);
            }

            return response()->json(['data' => $detalle], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener detalle',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(RequestDetallePedido $request, string $id): JsonResponse
    {
        try {
            $detalle = $this->detalleService->actualizarDetalle($id, $request->validated());

            if (!$detalle) {
                return response()->json(['error' => 'Detalle no encontrado'], 404);
            }

            return response()->json([
                'message' => 'Detalle actualizado correctamente',
                'data' => $detalle,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar detalle',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $eliminado = $this->detalleService->eliminarDetalle((int)$id);

            if (!$eliminado) {
                return response()->json(['error' => 'Detalle no encontrado'], 404);
            }

            return response()->json(['message' => 'Detalle eliminado exitosamente'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar detalle',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function restore(string $id): JsonResponse
    {
        try {
            $restaurado = $this->detalleService->restaurarDetalle((int)$id);

            if (!$restaurado) {
                return response()->json([
                    'error' => 'Detalle no encontrado o no está eliminado'
                ], 404);
            }

            return response()->json(['message' => 'Detalle restaurado exitosamente'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al restaurar detalle',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

