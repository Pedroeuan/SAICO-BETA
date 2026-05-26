<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;

class HistorialLlantaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'marca' => is_string($this->input('marca')) ? trim($this->input('marca')) : $this->input('marca'),
            'modelo' => is_string($this->input('modelo')) ? trim($this->input('modelo')) : $this->input('modelo'),
            'medida' => is_string($this->input('medida')) ? trim($this->input('medida')) : $this->input('medida'),
            'numero_serie' => is_string($this->input('numero_serie')) ? trim($this->input('numero_serie')) : $this->input('numero_serie'),
            'observaciones' => is_string($this->input('observaciones')) ? trim($this->input('observaciones')) : $this->input('observaciones'),
        ]);
    }

    public function rules(): array
    {
        return [
            'posicion' => 'required|in:delantera_izquierda,delantera_derecha,trasera_izquierda,trasera_derecha,refaccion,extra',
            'marca' => 'required|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'medida' => 'nullable|string|max:50',
            'numero_serie' => 'nullable|string|max:120',
            'fecha_instalacion' => 'required|date',
            'kilometraje_instalacion' => 'required|integer|min:0',
            'fecha_baja' => 'nullable|date|after_or_equal:fecha_instalacion',
            'kilometraje_baja' => 'nullable|integer|gte:kilometraje_instalacion',
            'costo' => 'nullable|numeric|min:0|max:99999999.99',
            'estado' => 'required|in:activa,rotada,baja',
            'observaciones' => 'nullable|string|max:1000',
        ];
    }
}
