<?php

namespace App\Http\Requests\Publicaciones;

use App\Enums\RedSocial;
use App\Enums\TipoPublicacion;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePublicacionRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'programar_publicacion' => $this->boolean('programar_publicacion'),
            'republicar_redes' => $this->boolean('republicar_redes'),
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $redesHabilitadas = config('publicaciones.redes_habilitadas', ['facebook']);
        $publicacion = $this->route('publicacion');

        return [
            'titulo' => ['required', 'string', 'min:5', 'max:150'],
            'contenido' => ['required', 'string', 'min:20', 'max:3000'],
            'tipo' => ['required', Rule::in(array_column(TipoPublicacion::cases(), 'value'))],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
            'imagen_alt' => ['nullable', 'string', 'max:200'],
            'url_destino' => ['nullable', 'url', 'max:500'],
            'redes' => ['required', 'array', 'min:1'],
            'redes.*' => [Rule::in(array_values(array_intersect(array_column(RedSocial::cases(), 'value'), $redesHabilitadas)))],
            'republicar_redes' => ['nullable', 'boolean', Rule::prohibitedIf(fn (): bool => $this->boolean('programar_publicacion'))],
            'programar_publicacion' => ['nullable', 'boolean'],
            'programado_at' => [
                'nullable',
                Rule::requiredIf(fn (): bool => $this->boolean('programar_publicacion')),
                'date',
                function (string $attribute, mixed $value, \Closure $fail) use ($publicacion): void {
                    if (!$this->boolean('programar_publicacion') || blank($value)) {
                        return;
                    }

                    $fechaNueva = Carbon::parse((string) $value);
                    $fechaActual = $publicacion?->programado_at;

                    if ($fechaActual !== null && $fechaActual->format('Y-m-d H:i:s') === $fechaNueva->format('Y-m-d H:i:s')) {
                        return;
                    }

                    if ($fechaNueva->lessThanOrEqualTo(now())) {
                        $fail('La fecha programada debe ser posterior a la fecha y hora actual.');
                    }
                },
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'titulo.required' => 'El titulo es obligatorio.',
            'titulo.min' => 'El titulo debe tener al menos 5 caracteres.',
            'titulo.max' => 'El titulo no puede superar los 150 caracteres.',
            'contenido.required' => 'El contenido es obligatorio.',
            'contenido.min' => 'El contenido debe tener al menos 20 caracteres.',
            'contenido.max' => 'El contenido no puede superar los 3000 caracteres.',
            'tipo.required' => 'Debes seleccionar un tipo de publicacion.',
            'tipo.in' => 'El tipo de publicacion seleccionado no es valido.',
            'imagen.image' => 'El archivo seleccionado debe ser una imagen real.',
            'imagen.mimes' => 'La imagen debe estar en formato JPEG, PNG o WEBP.',
            'imagen.max' => 'La imagen no debe exceder 5 MB.',
            'imagen_alt.max' => 'El texto alternativo no puede superar los 200 caracteres.',
            'url_destino.url' => 'La URL de destino debe ser valida.',
            'url_destino.max' => 'La URL de destino no puede superar los 500 caracteres.',
            'redes.required' => 'Selecciona al menos una red social.',
            'redes.array' => 'Las redes sociales deben enviarse como una lista valida.',
            'redes.min' => 'Selecciona al menos una red social objetivo.',
            'redes.*.in' => 'La red social seleccionada no esta habilitada en este momento.',
            'republicar_redes.boolean' => 'La opcion de republicar debe ser valida.',
            'republicar_redes.prohibited_if' => 'No puedes programar y publicar de inmediato al mismo tiempo.',
            'programado_at.required_if' => 'Debes indicar la fecha y hora cuando activas la programacion.',
            'programado_at.date' => 'La fecha programada no tiene un formato valido.',
            'programado_at.after' => 'La fecha programada debe ser posterior a la fecha y hora actual.',
        ];
    }
}
