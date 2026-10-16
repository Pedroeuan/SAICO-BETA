<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;

class SeleccionReferencias0402
{
    public static function filtrar(array $candidatas, string $json): array
    {
        try {
            $claves = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw ValidationException::withMessages(['Referencias_Seleccionadas_0402' => 'No se pudo leer la selección de normas.']);
        }
        $claves = validator(['seleccion' => $claves], [
            'seleccion' => 'present|array|max:100',
            'seleccion.*' => ['required', 'string', 'distinct', 'regex:/^\d+:\d+$/'],
        ])->validate()['seleccion'];
        $disponibles = [];
        foreach ($candidatas as $fila) $disponibles[$fila['idnormas_im'].':'.$fila['fila']] = $fila;
        $resultado = [];
        foreach ($claves as $clave) {
            if (!isset($disponibles[$clave])) throw ValidationException::withMessages(['Referencias_Seleccionadas_0402' => 'Una referencia marcada ya no está disponible en el catálogo. Revisa y vuelve a seleccionarla.']);
            $resultado[] = $disponibles[$clave];
        }

        return $resultado;
    }
}
