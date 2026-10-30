<?php

namespace App\Http\Requests\Mobile;

use Illuminate\Foundation\Http\FormRequest;

class MobileAnalyticsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'desde' => ['nullable', 'date_format:Y-m-d'],
            'hasta' => array_merge(['nullable', 'date_format:Y-m-d'], $this->filled('desde') ? ['after_or_equal:desde'] : []),
            // An unknown or invisible vehicle yields an empty result, not an existence disclosure.
            'vehiculo_id' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
