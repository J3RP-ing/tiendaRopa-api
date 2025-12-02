<?php

namespace App\Services;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Collection;

class PedidoService
{
    public function obtenerPedidos(): Collection
    {
        return Pedido::with(['usuario', 'direccion', 'detallePedido'])->get();
    }

    public function crearPedido(array $data): ?Pedido
    {
        // Prevenir crear pedido sin usuario
        if (! isset($data['id_usuario'])) {
            return null;
        }

        return Pedido::create($data);
    }

    public function obtenerPedidoPorId(int $id): ?Pedido
    {
        return Pedido::with(['usuario', 'direccion', 'detallePedido'])->find($id);
    }

    public function actualizarPedido(int $id, array $data): ?Pedido
    {
        $pedido = Pedido::find($id);

        if (! $pedido) {
            return null;
        }

        $pedido->update($data);
        return $pedido;
    }

    public function eliminarPedido(int $id): bool
    {
        $pedido = Pedido::find($id);

        if (! $pedido) {
            return false;
        }

        $pedido->delete();
        return true;
    }

    public function restaurarPedido(int $id): bool
    {
        $pedido = Pedido::withTrashed()->find($id);

        if (! $pedido || ! $pedido->trashed()) {
            return false;
        }

        $pedido->restore();
        return true;
    }
}
