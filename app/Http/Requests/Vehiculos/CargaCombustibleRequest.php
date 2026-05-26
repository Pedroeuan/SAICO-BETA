<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;

class CargaCombustibleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $proveedor = $this->input('proveedor');
        $observaciones = $this->input('observaciones');
        $litros = $this->input('litros');
        $costoTotal = $this->input('costo_total');

        $payload = [
            'proveedor' => is_string($proveedor) ? trim($proveedor) : $proveedor,
            'observaciones' => is_string($observaciones) ? trim($observaciones) : $observaciones,
            'tanque_lleno' => $this->boolean('tanque_lleno'),
        ];

        if (is_numeric($litros) && (float) $litros > 0 && is_numeric($costoTotal)) {
            $payload['precio_por_litro'] = round(((float) $costoTotal) / ((float) $litros), 4);
        }

        $this->merge($payload);
    }

    public function rules(): array
    {
        return [
            'fecha_carga' => 'required|date',
            'kilometraje' => 'required|integer|min:0',
            'litros' => 'required|numeric|gt:0|max:999999.999',
            'costo_total' => 'required|numeric|min:0|max:99999999.99',
            'precio_por_litro' => 'nullable|numeric|min:0|max:999999.9999',
            'tipo_combustible' => 'required|in:magna,premium,diesel,otro',
            'proveedor' => 'nullable|string|max:150',
            'tanque_lleno' => 'nullable|boolean',
            'ticket_url' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'observaciones' => 'nullable|string|max:1000',
        ];
    }
}
