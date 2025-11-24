<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestPedido extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH']);

        return [
            'id_usuario' =>($isUpdate ? 'sometimes' : 'required') . '|exists:tblUsuarios,id_usuario|integer',
            'fecha_pedido' => ($isUpdate ? 'sometimes' : 'nullable') . '|date',
            'total' => ($isUpdate ? 'sometimes' : 'nullable') . '|numeric|min:0',
            'estado' => 'boolean',
            'direccion_envio' => ($isUpdate ? 'sometimes' : 'required') . '|string|max:255',
            'metodo_pago' => ($isUpdate ? 'sometimes' : 'requerided') . '|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'id_usuario.required' => 'El usuario es obligatorio.',
            'id_usuario.exists' => 'El usuario seleccionado no existe.',

            'direccion_envio.required' => 'La dirección de envío es obligatoria.',

            'total.numeric' => 'El total debe ser un valor numérico.',
            'total.min' => 'El total debe ser mínimo 0.',
            
            'metodo_pago' => 'El metodo de pago es obligatorio',
            'metodo_pago.max' => 'El método de pago no puede exceder los 100 caracteres.',
        ];
    }
}
