<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;

class VehiculoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'placa' => strtoupper(trim($this->placa)),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'placa'   => 'required|string|max:100|unique:vehiculos,placa,'. $this->route('id'),
            'marca'   => 'required|string|max:100',
            'modelo'  => 'required|string|max:100',
            'anio'    => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'estatus' => 'required|in:disponible,ocupado,inactivo',
            'foto_principal' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'tenencia_vencimiento' => 'nullable|date',
            'tenencia_estatus' => 'nullable|in:vigente,proxima,vencida,sin_registro',

            // Documentación del vehículo
            'poliza_seguro_pdf' => 'nullable|file|mimes:pdf|max:5120',
            'poliza_seguro_vencimiento' => 'nullable|date',
            'tarjeta_circulacion_pdf' => 'nullable|file|mimes:pdf|max:5120',
            'tarjeta_circulacion_vencimiento' => 'nullable|date',

        ];
    }

}
