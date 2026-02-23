<?php
namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;

class SalidaVehiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ya pasas auth + gates
    }

    public function rules(): array
    {
        return [
            'vehiculo_id'    => 'required|exists:vehiculos,id',
            'chofer_id'      => 'required|exists:users,id',
            'solicitado_por' => 'nullable|exists:users,id',
            'fecha_salida'   => 'required|date',
            'fecha_regreso'  => 'nullable|date|after_or_equal:fecha_salida',
            'motivo'         => 'nullable|string|max:255',
            ''
        ];
    }
}
