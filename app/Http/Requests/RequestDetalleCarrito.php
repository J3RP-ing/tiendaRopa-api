<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestDetalleCarrito extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH']);

        return [
            'id_carrito' =>($isUpdate ? 'sometimes' : 'required') . '|exists:tblCarrito,id_carrito',
            'id_producto' =>($isUpdate ? 'sometimes' : 'required') . '|exists:tblProductos,id_producto',
            'id_personalizacion' => 'nullable|exists:tblPersonalizacion,id_personalizacion',
            'cantidad' =>($isUpdate ? 'sometimes' : 'required') . '|integer|min:1',
            'subtotal' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'id_carrito.required' => 'El carrito es obligatorio.',
            'id_carrito.exists' => 'El carrito seleccionado no existe.',

            'id_producto.required' => 'El producto es obligatorio.',
            'id_producto.exists' => 'El producto seleccionado no existe.',

            'id_personalizacion.exists' => 'La personalización no existe.',

            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad mínima es 1.',

            'subtotal.numeric' => 'El subtotal debe ser numérico.',
            'subtotal.min' => 'El subtotal no puede ser negativo.',
        ];
    }
}
