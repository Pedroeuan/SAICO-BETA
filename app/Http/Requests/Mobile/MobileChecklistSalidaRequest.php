<?php

namespace App\Http\Requests\Mobile;

use Illuminate\Foundation\Http\FormRequest;

class MobileChecklistSalidaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nivel_gasolina' => ['required', 'string'],
            'kilometraje' => ['required', 'integer', 'min:0'],
            'limpio_exterior' => ['nullable', 'in:0,1'],
            'limpio_interior' => ['nullable', 'in:0,1'],
            'observaciones' => ['nullable', 'string', 'max:500'],
            'liquido_limpiaparabrisas' => ['required', 'in:suficiente,escaso,no_hay'],
            'aceite' => ['required', 'in:suficiente,escaso,no_hay'],
            'anticongelante' => ['required', 'in:suficiente,escaso,no_hay'],
            'liquido_frenos' => ['required', 'in:suficiente,escaso,no_hay'],
            'estado_llantas' => ['required', 'in:buen_estado,regular,malo'],
            'llanta_delantera_izq_calibracion' => ['required', 'in:baja,normal,alta'],
            'llanta_delantera_der_calibracion' => ['required', 'in:baja,normal,alta'],
            'llanta_trasera_izq_calibracion' => ['required', 'in:baja,normal,alta'],
            'llanta_trasera_der_calibracion' => ['required', 'in:baja,normal,alta'],
            'herramientas' => ['nullable', 'array'],
            'evidencias' => ['required', 'array', 'size:3'],
            'evidencias.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'nivel_gasolina.required' => 'El nivel de gasolina es obligatorio.',
            'kilometraje.required' => 'El kilometraje es obligatorio.',
            'kilometraje.integer' => 'El kilometraje debe ser numerico.',
            'kilometraje.min' => 'El kilometraje no puede ser negativo.',
            'observaciones.max' => 'Las observaciones no pueden superar 500 caracteres.',
            'liquido_limpiaparabrisas.required' => 'Debes indicar el liquido limpiaparabrisas.',
            'aceite.required' => 'Debes indicar el nivel de aceite.',
            'anticongelante.required' => 'Debes indicar el nivel de anticongelante.',
            'liquido_frenos.required' => 'Debes indicar el liquido de frenos.',
            'estado_llantas.required' => 'Debes indicar el estado general de llantas.',
            'llanta_delantera_izq_calibracion.required' => 'Debes indicar la llanta delantera izquierda.',
            'llanta_delantera_der_calibracion.required' => 'Debes indicar la llanta delantera derecha.',
            'llanta_trasera_izq_calibracion.required' => 'Debes indicar la llanta trasera izquierda.',
            'llanta_trasera_der_calibracion.required' => 'Debes indicar la llanta trasera derecha.',
            'evidencias.required' => 'Debes adjuntar las evidencias fotograficas.',
            'evidencias.array' => 'Las evidencias deben enviarse como arreglo.',
            'evidencias.size' => 'Debes adjuntar exactamente 3 evidencias.',
            'evidencias.*.image' => 'Cada evidencia debe ser una imagen valida.',
            'evidencias.*.mimes' => 'Las evidencias deben estar en formato jpg, jpeg, png o webp.',
            'evidencias.*.max' => 'Cada evidencia puede pesar maximo 5 MB.',
        ];
    }
}
