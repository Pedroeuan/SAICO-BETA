<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;

class ReferenciasManuales0402
{
    public function validar(string $json): array
    {
        try {
            $filas = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw ValidationException::withMessages(['Referencias_Manuales_0402' => 'No se pudo leer la captura manual de normas.']);
        }
        if (is_array($filas)) {
            foreach ($filas as &$fila) {
                if (!is_array($fila)) continue;
                foreach (['dureza_max', 'tension_min', 'cedencia_min', 'tension_max'] as $campo) {
                    if (isset($fila[$campo]) && is_string($fila[$campo])) $fila[$campo] = trim(str_replace(',', '.', $fila[$campo]));
                    if (isset($fila[$campo]) && is_string($fila[$campo]) && preg_match('/^(?:\s*|[-–—]+|n\/?a|no aplica|no especificado)$/iu', $fila[$campo])) $fila[$campo] = null;
                }
            }
            unset($fila);
            $filas = array_values(array_filter($filas, fn ($fila) => !is_array($fila) || count(array_filter($fila, fn ($v) => $v !== null && $v !== '')) > 0));
        }
        $datos = validator(['referencias' => $filas], [
            'referencias' => 'present|array|max:30',
            'referencias.*' => 'array:norma,dureza_max,tension_min,cedencia_min,tension_max',
            'referencias.*.norma' => 'required|string|max:255',
            'referencias.*.dureza_max' => 'nullable|numeric|min:0',
            'referencias.*.tension_min' => 'nullable|numeric|min:0',
            'referencias.*.cedencia_min' => 'nullable|numeric|min:0',
            'referencias.*.tension_max' => 'nullable|numeric|min:0',
        ])->validate()['referencias'];
        foreach ($datos as $fila) {
            if (isset($fila['tension_min'], $fila['tension_max']) && $fila['tension_min'] > $fila['tension_max']) throw ValidationException::withMessages(['Referencias_Manuales_0402' => 'La tensión máxima no puede ser menor que la mínima.']);
        }

        return $datos;
    }
}
