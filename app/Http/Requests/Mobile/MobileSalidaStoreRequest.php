<?php

namespace App\Http\Requests\Mobile;

use Illuminate\Foundation\Http\FormRequest;

class MobileSalidaStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'chofer_id' => ['required', 'exists:users,id'],
            'solicitado_por' => ['nullable', 'exists:users,id'],
            'fecha_salida' => ['required', 'date'],
            'motivo' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehiculo_id.required' => 'Debes seleccionar un vehiculo.',
            'vehiculo_id.exists' => 'El vehiculo seleccionado no existe.',
            'chofer_id.required' => 'Debes seleccionar un chofer.',
            'chofer_id.exists' => 'El chofer seleccionado no existe.',
            'solicitado_por.exists' => 'El solicitante seleccionado no existe.',
            'fecha_salida.required' => 'La fecha de salida es obligatoria.',
            'fecha_salida.date' => 'La fecha de salida no es valida.',
            'motivo.max' => 'El motivo no puede superar 255 caracteres.',
        ];
    }
}
