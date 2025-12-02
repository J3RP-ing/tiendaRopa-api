<?php

namespace App\Services;

use App\Models\DetallePedido;
use App\Models\Producto;
use App\Models\Personalizacion;
use Illuminate\Database\Eloquent\Collection;

class DetallePedidoService
{
    public function obtenerDetalles(): Collection
    {
        return DetallePedido::with(['pedido', 'producto', 'personalizacion'])->get();
    }

    public function crearDetalle(array $data): ?DetallePedido
    {
        // Calcular subtotal automáticamente si no viene
        if (!isset($data['subtotal'])) {
            $producto = Producto::find($data['id_producto']);
            if (!$producto) {
                return null;
            }

            $precioUnitario = $data['precio_unitario'] ?? $producto->precio;
            $subtotal = $precioUnitario * $data['cantidad'];

            // Si tiene personalización → suma
            if (!empty($data['id_personalizacion'])) {
                $pers = Personalizacion::find($data['id_personalizacion']);
                if ($pers) {
                    $subtotal += $pers->precio_total_personalizacion;
                }
            }

            $data['subtotal'] = $subtotal;
            $data['precio_unitario'] = $precioUnitario;
        }

        return DetallePedido::create($data);
    }

    public function obtenerDetallePorId($id): ?DetallePedido
    {
        return DetallePedido::with(['pedido', 'producto', 'personalizacion'])->find($id);
    }

    public function actualizarDetalle($id, array $data): ?DetallePedido
    {
        $detalle = DetallePedido::find($id);

        if (!$detalle) {
            return null;
        }

        $detalle->update($data);
        return $detalle;
    }

    public function eliminarDetalle($id): bool
    {
        $detalle = DetallePedido::find($id);

        if (!$detalle) {
            return false;
        }

        $detalle->delete();
        return true;
    }

    public function restaurarDetalle($id): bool
    {
        $detalle = DetallePedido::withTrashed()->find($id);

        if (!$detalle || !$detalle->trashed()) {
            return false;
        }

        $detalle->restore();
        return true;
    }
}

