<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;

class ComposicionesReferencias0402
{
    public static function notas(array $referencias): array
    {
        $notas = [];
        foreach ($referencias as $indice => $referencia) {
            $texto = trim((string) ($referencia['composicion_seleccionada']['Notas_Tecnico'] ?? $referencia['composicion_seleccionada']['Observaciones'] ?? ''));
            if ($texto !== '') $notas[] = ['indice' => $indice, 'norma' => $referencia['norma'], 'texto' => $texto];
        }
        return $notas;
    }

    public static function seleccionar(array $referencias, array $selecciones): array
    {
        foreach ($referencias as &$referencia) {
            $clave = $referencia['idnormas_im'].':'.$referencia['fila'];
            $opciones = $referencia['composiciones'] ?? [];
            unset($referencia['composiciones']);
            $indice = $selecciones[$clave] ?? (count($opciones) === 1 ? $opciones[0]['configuracion'] : '');
            $referencia['composicion_seleccionada'] = null;
            if ($indice === '' || $indice === null) continue;
            foreach ($opciones as $opcion) {
                if ((string) $opcion['configuracion'] === (string) $indice) $referencia['composicion_seleccionada'] = $opcion;
            }
            if (!$referencia['composicion_seleccionada']) throw ValidationException::withMessages(['Composiciones_Referencias_0402' => 'La composición seleccionada no pertenece a la norma marcada.']);
        }
        unset($referencia);
        return $referencias;
    }

    public static function filas(array $referencias): array
    {
        $filas = [];
        foreach ($referencias as $indice => $referencia) {
            foreach (($referencia['composicion_seleccionada']['Tabla'] ?? []) as $fila) {
                $elemento = trim((string) ($fila['Elemento'] ?? ''));
                if ($elemento === '') continue;
                $clave = mb_strtolower($elemento);
                $filas[$clave] ??= ['Elemento' => $elemento, 'Composicion' => '', 'Referencias' => []];
                $filas[$clave]['Referencias'][] = ['indice' => $indice, 'norma' => $referencia['norma'], 'valor' => $fila['Composicion'] ?? ''];
            }
        }
        return array_values($filas);
    }
}
