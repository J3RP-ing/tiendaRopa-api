<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestDetallePedido extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH']);

        return [
            'id_pedido' => ($isUpdate ? 'sometimes' : 'required') . '|integer|exists:tblPedidos,id_pedido',
            'id_producto' => ($isUpdate ? 'sometimes' : 'required') . '|integer|exists:tblProductos,id_producto',
            'id_personalizacion' => 'nullable|integer|exists:tblPersonalizacion,id_personalizacion',
            'cantidad' => ($isUpdate ? 'sometimes' : 'required') . '|integer|min:1',
            'precio_unitario' => ($isUpdate ? 'sometimes' : 'required') . '|numeric|min:0',
            'subtotal' => ($isUpdate ? 'sometimes' : 'required') . '|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'id_pedido.required' => 'El pedido es obligatorio.',
            'id_pedido.integer' => 'El pedido debe ser un número entero.',
            'id_pedido.exists' => 'El pedido seleccionado no existe.',

            'id_producto.required' => 'El producto es obligatorio.',
            'id_producto.integer' => 'El producto debe ser un número entero.',
            'id_producto.exists' => 'El producto seleccionado no existe.',

            'id_personalizacion.integer' => 'La personalización debe ser un número entero.',
            'id_personalizacion.exists' => 'La personalización seleccionada no existe.',

            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad mínima es 1.',

            'precio_unitario.required' => 'El precio unitario es obligatorio.',
            'precio_unitario.numeric' => 'El precio unitario debe ser numérico.',
            'precio_unitario.min' => 'El precio unitario no puede ser negativo.',

            'subtotal.required' => 'El subtotal es obligatorio.',
            'subtotal.numeric' => 'El subtotal debe ser numérico.',
            'subtotal.min' => 'El subtotal no puede ser negativo.',
        ];
    }
}

