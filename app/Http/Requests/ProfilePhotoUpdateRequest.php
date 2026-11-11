<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilePhotoUpdateRequest extends FormRequest
{
    // La ruta está protegida por auth; esta comprobación mantiene la regla explícita.
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            // Laravel vuelve a validar el archivo aunque el navegador ya lo haya revisado.
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=4096,max_height=4096'],
            // Este endpoint únicamente modifica la fotografía del usuario autenticado.
            'name' => ['prohibited'],
            'email' => ['prohibited'],
            'password' => ['prohibited'],
            'rol' => ['prohibited'],
        ];
    }
}
