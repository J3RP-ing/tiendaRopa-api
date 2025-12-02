<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestPago extends FormRequest
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
            'fecha_pago' => ($isUpdate ? 'sometimes' : 'nullable') . '|date',
            'metodo' => ($isUpdate ? 'sometimes' : 'required') . '|string|max:50',
            'monto' => ($isUpdate ? 'sometimes' : 'required') . '|numeric|min:0',
            'estado' => ($isUpdate ? 'sometimes' : 'nullable') . '|in:pendiente,procesando,aprobado,rechazado,fallido,reembolsado,cancelado',
            'referencia_transaccion' => 'nullable|string|max:255|unique:tblPagos,referencia_transaccion',
        ];
    }

    public function messages(): array
    {
        return [
            'id_pedido.required' => 'El pedido es obligatorio.',
            'id_pedido.integer' => 'El pedido debe ser un número entero.',
            'id_pedido.exists' => 'El pedido seleccionado no existe.',

            'fecha_pago.date' => 'La fecha de pago debe ser una fecha válida.',

            'metodo.required' => 'El método de pago es obligatorio.',
            'metodo.string' => 'El método de pago debe ser un texto.',
            'metodo.max' => 'El método de pago no debe exceder los 50 caracteres.',

            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un valor numérico.',
            'monto.min' => 'El monto no puede ser negativo.',

            'estado.in' => 'El estado debe ser uno de: pendiente, procesando, aprobado, rechazado, fallido, reembolsado, cancelado.',

            'referencia_transaccion.string' => 'La referencia de transacción debe ser un texto.',
            'referencia_transaccion.max' => 'La referencia de transacción no debe exceder los 255 caracteres.',
            'referencia_transaccion.unique' => 'Esta referencia de transacción ya está registrada.',
        ];
    }
}

