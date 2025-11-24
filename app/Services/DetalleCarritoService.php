<?php

namespace App\Services;

use App\Models\DetalleCarrito;
use App\Models\Producto;
use App\Models\Personalizacion;
use Illuminate\Database\Eloquent\Collection;

class DetalleCarritoService
{
    public function obtenerDetalles(): Collection
    {
        return DetalleCarrito::with(['producto', 'personalizacion'])->get();
    }

    public function crearDetalle(array $data): ?DetalleCarrito
    {
        // Calcular subtotal automáticamente si no viene
        if (!isset($data['subtotal'])) {

            $producto = Producto::find($data['id_producto']);
            if (!$producto) {
                return null;
            }//ver si va con corchetes en esta parte
            $subtotal = $producto->precio * $data['cantidad'];

            // Si tiene personalización → suma
            if (!empty($data['id_personalizacion'])) {
                $pers = Personalizacion::find($data['id_personalizacion']);
                if ($pers) {
                    $subtotal += $pers->precio_total_personalizacion;
                }
            }

            $data['subtotal'] = $subtotal;
        }

        return DetalleCarrito::create($data);
    }

    public function obtenerDetallePorId($id): ?DetalleCarrito
    {
        return DetalleCarrito::find($id);
    }

    public function actualizarDetalle($id, array $data): ?DetalleCarrito
    {
        $detalle = DetalleCarrito::find($id);

        if (!$detalle) {
            return null;
        }

        $detalle->update($data);
        return $detalle;
    }

    public function eliminarDetalle($id): bool
    {
        $detalle = DetalleCarrito::find($id);

        if (!$detalle) {
            return false;
        }

        $detalle->delete();
        return true;
    }

    public function restaurarDetalle($id): bool
    {
        $detalle = DetalleCarrito::withTrashed()->find($id);

        if (!$detalle || !$detalle->trashed()) {
            return false;
        }

        $detalle->restore();
        return true;
    }
}
