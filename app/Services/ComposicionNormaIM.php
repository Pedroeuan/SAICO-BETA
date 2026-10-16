<?php

namespace App\Services;

class ComposicionNormaIM
{
    public static function normalizarLimites(array $configuraciones): array
    {
        foreach ($configuraciones as &$config) {
            foreach ($config['elementos'] as &$fila) {
                if (($fila['minimo'] ?? '') !== '' && ($fila['minimo'] ?? null) !== null) continue;
                if (($fila['maximo'] ?? '') !== '' && ($fila['maximo'] ?? null) !== null) continue;
                $texto = trim((string) ($fila['criterio'] ?? ''));
                $numero = '\d+(?:[.,]\d+)?';
                if (preg_match('/^('.$numero.')\s*%?\s*(m[áa]x(?:imo|imum)?|m[íi]n(?:imo|imum)?)\.?\s*%?$/iu', $texto, $m)) {
                    $campo = preg_match('/^m[áa]x/iu', $m[2]) ? 'maximo' : 'minimo';
                    if ((float) str_replace(',', '.', $m[1]) > 100) continue;
                    $fila[$campo] = str_replace(',', '.', $m[1]);
                    $fila['criterio'] = '';
                } elseif (preg_match('/^('.$numero.')\s*[-–—]\s*('.$numero.')\s*%?$/u', $texto, $m)) {
                    $min = str_replace(',', '.', $m[1]); $max = str_replace(',', '.', $m[2]);
                    if ((float) $min > (float) $max || (float) $max > 100) continue;
                    $fila['minimo'] = $min; $fila['maximo'] = $max; $fila['criterio'] = '';
                }
            }
            unset($fila);
        }
        unset($config);
        return $configuraciones;
    }

    public static function abreviarLimites(string $texto): string
    {
        return preg_replace(['/\bm[íi]nimo\b/iu', '/\bm[áa]ximo\b/iu'], ['mín.', 'máx.'], $texto);
    }

    public static function desdeFilas(array $filas): array
    {
        $grupos = [];
        foreach ($filas as $fila) {
            if (empty($fila['Elemento'])) {
                continue;
            }
            $contexto = $fila['Aplicabilidad'] ?? [];
            $clave = json_encode($contexto);
            if (! isset($grupos[$clave])) {
                $grupos[$clave] = $contexto + ['grado' => '', 'elementos' => []];
            }
            $grupos[$clave]['elementos'][] = ['elemento' => $fila['Elemento'], 'minimo' => null, 'maximo' => null, 'criterio' => $fila['Composicion'] ?? '', 'condicion' => ''];
        }

        return self::normalizarLimites(array_values($grupos));
    }

    public static function validar(string $json): array
    {
        try {
            $datos = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw \Illuminate\Validation\ValidationException::withMessages(['Quimica_Configuraciones' => 'No se pudo leer la composición química.']);
        }
        $reglas = ['configuraciones' => 'present|array|max:50', 'configuraciones.*' => 'array:producto,ancho,espesor,grado,condiciones,elementos'];
        foreach (['producto', 'ancho', 'espesor', 'grado', 'condiciones'] as $campo) {
            $reglas['configuraciones.*.'.$campo] = 'nullable|string|max:2000';
        }
        $reglas += [
            'configuraciones.*.elementos' => 'required|array|min:1|max:100',
            'configuraciones.*.elementos.*' => 'array:elemento,minimo,maximo,criterio,condicion',
            'configuraciones.*.elementos.*.elemento' => 'required|string|max:100',
            'configuraciones.*.elementos.*.minimo' => 'nullable|numeric|between:0,100',
            'configuraciones.*.elementos.*.maximo' => 'nullable|numeric|between:0,100',
            'configuraciones.*.elementos.*.criterio' => 'nullable|string|max:2000',
            'configuraciones.*.elementos.*.condicion' => 'nullable|string|max:2000',
        ];
        $datos = validator(['configuraciones' => $datos], $reglas)->validate()['configuraciones'];
        $claves = [];
        foreach ($datos as $i => $config) {
            $clave = implode('|', array_map(fn ($campo) => mb_strtolower(trim($config[$campo] ?? '')), ['producto', 'ancho', 'espesor', 'grado']));
            if (isset($claves[$clave])) {
                throw \Illuminate\Validation\ValidationException::withMessages(['Quimica_Configuraciones' => 'Hay configuraciones repetidas de producto, ancho, espesor y grado.']);
            }
            $claves[$clave] = true;
            $elementos = [];
            foreach ($config['elementos'] as $fila) {
                $simbolo = mb_strtolower(trim($fila['elemento']));
                if (isset($elementos[$simbolo])) {
                    throw \Illuminate\Validation\ValidationException::withMessages(['Quimica_Configuraciones' => 'Un elemento está repetido dentro de una configuración.']);
                }
                $elementos[$simbolo] = true;
                if (isset($fila['minimo'], $fila['maximo']) && $fila['minimo'] > $fila['maximo']) {
                    throw \Illuminate\Validation\ValidationException::withMessages(['Quimica_Configuraciones' => 'El mínimo no puede superar el máximo en '.$fila['elemento'].'.']);
                }
            }
        }

        return self::normalizarLimites($datos);
    }
}
