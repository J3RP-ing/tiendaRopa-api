<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestCarrito extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH']);

        return [
            'id_usuario' =>($isUpdate ? 'sometimes' : 'required') . '|exists:tblUsuarios,id_usuario',
            'estado' => 'boolean',
            'total_carrito' => ($isUpdate ? 'sometimes' : 'required') . '|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'id_usuario.required' => 'El usuario es obligatorio.',
            'id_usuario.exists' => 'El usuario seleccionado no existe.',
            'total_carrito.required' => 'El total del carrito es obligatorio.',
            'total_carrito.numeric' => 'El total debe ser un valor numérico.',
            'total_carrito.min' => 'El total debe ser mínimo 0.',
        ];
    }
}
