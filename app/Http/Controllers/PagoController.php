<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestPago;
use App\Services\PagoService;
use Illuminate\Http\JsonResponse;

class PagoController extends Controller
{
    protected $pagoService;

    public function __construct(PagoService $pagoService)
    {
        $this->pagoService = new $pagoService;
    }

    public function index(): JsonResponse
    {
        try {
            $pagos = $this->pagoService->obtenerPagos();

            return response()->json(['data' => $pagos], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los pagos',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(RequestPago $request): JsonResponse
    {
        try {
            $pago = $this->pagoService->crearPago($request->validated());

            return response()->json([
                'message' => 'Pago creado exitosamente',
                'data' => $pago,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el pago',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $pago = $this->pagoService->obtenerPagoPorId((int) $id);

            if (! $pago) {
                return response()->json(['error' => 'Pago no encontrado'], 404);
            }

            return response()->json(['data' => $pago], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el pago',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(RequestPago $request, string $id): JsonResponse
    {
        try {
            $pago = $this->pagoService->actualizarPago($id, $request->validated());

            if (! $pago) {
                return response()->json(['error' => 'Pago no encontrado'], 404);
            }

            return response()->json([
                'message' => 'Pago actualizado exitosamente',
                'data' => $pago,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el pago',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $eliminado = $this->pagoService->eliminarPago((int) $id);

            if (! $eliminado) {
                return response()->json(['error' => 'Pago no encontrado'], 404);
            }

            return response()->json(['message' => 'Pago eliminado exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el pago',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function restore(string $id): JsonResponse
    {
        try {
            $restaurado = $this->pagoService->restaurarPago((int) $id);

            if (! $restaurado) {
                return response()->json(['error' => 'Pago no encontrado o no está eliminado'], 404);
            }

            return response()->json(['message' => 'Pago restaurado exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al restaurar el pago',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

