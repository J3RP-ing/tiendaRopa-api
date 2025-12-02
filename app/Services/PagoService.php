<?php

namespace App\Services;

use App\Models\Pago;
use Illuminate\Database\Eloquent\Collection;

class PagoService
{
    public function obtenerPagos(): Collection
    {
        return Pago::with('pedido')->get();
    }

    public function crearPago(array $data): Pago
    {
        return Pago::create($data);
    }

    public function obtenerPagoPorId($id): ?Pago
    {
        return Pago::with('pedido')->find($id);
    }

    public function actualizarPago($id, array $data): ?Pago
    {
        $pago = Pago::find($id);

        if (! $pago) {
            return null;
        }

        $pago->update($data);

        return $pago;
    }

    public function eliminarPago($id): bool
    {
        $pago = Pago::find($id);

        if (! $pago) {
            return false;
        }

        $pago->delete();

        return true;
    }

    public function restaurarPago($id): bool
    {
        $pago = Pago::withTrashed()->find($id);

        if (! $pago || ! $pago->trashed()) {
            return false;
        }

        $pago->restore();

        return true;
    }
}

