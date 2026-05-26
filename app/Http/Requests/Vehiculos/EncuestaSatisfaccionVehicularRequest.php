<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;

class EncuestaSatisfaccionVehicularRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $comentario = $this->input('comentario');

        $this->merge([
            'comentario' => is_string($comentario) ? trim($comentario) : $comentario,
        ]);
    }

    public function rules(): array
    {
        return [
            'calificacion_servicio' => 'required|integer|min:1|max:5',
            'calificacion_estado_unidad' => 'required|integer|min:1|max:5',
            'calificacion_tiempo_respuesta' => 'required|integer|min:1|max:5',
            'nps' => 'required|integer|min:0|max:10',
            'comentario' => 'nullable|string|max:1200',
        ];
    }
}
