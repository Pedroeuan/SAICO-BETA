<?php

namespace App\Services;

class ComparacionEspecificacion
{
    public static function comparar(string $criterio, string $norma, string $variable = ''): string
    {
        $normalizar = static fn (string $texto) => mb_strtoupper(
            preg_replace('/\s+/u', '', str_replace('·', '', $texto)), 'UTF-8'
        );
        if ($normalizar($criterio) === '' || $normalizar($norma) === '') {
            return 'sin_datos';
        }
        $referencia = trim($norma) . (trim($variable) !== '' ? ' · ' . trim($variable) : '');

        return $normalizar($criterio) === $normalizar($referencia) ? 'coincide' : 'no_coincide';
    }
}
