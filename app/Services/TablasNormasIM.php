<?php

namespace App\Services;

class TablasNormasIM
{
    public static function aplicabilidad(): array
    {
        return ['producto' => 'Producto (opcional)', 'ancho' => 'Ancho / intervalo y unidad (opcional)', 'espesor' => 'Espesor / intervalo y unidad (opcional)', 'condiciones' => 'Condiciones de aplicación (opcional)'];
    }

    public static function definiciones(): array
    {
        $tablas = [
            'tratamiento' => ['titulo' => 'TABLE 1 · Heat Treating Requirements', 'columnas' => [
                'grado' => 'Grado / variante', 'tratamiento' => 'Tipo de tratamiento',
                'temperatura' => 'Austenitizing / Solutioning Temperature, Minimum or Range, °F [°C]',
                'enfriamiento' => 'Medio de enfriamiento', 'enfriar_debajo' => 'Quenching Cool Below, °F [°C]',
                'revenido' => 'Tempering Temperature, Minimum or Range, °F [°C]', 'notas' => 'Notas / excepciones',
            ]],
            'mecanica' => ['titulo' => 'TABLE 3 · Tensile and Hardness Requirements', 'columnas' => [
                'grado' => 'Grado / variante',
                'tension_min' => 'Resistencia a la tensión mínima (indicar unidad)',
                'tension_max' => 'Resistencia a la tensión máxima (indicar unidad)',
                'fluencia' => 'Cedencia mínima (indicar unidad)', 'elongacion' => 'Elongación mínima (%) y longitud de referencia',
                'reduccion' => 'Reducción de área mínima (%)', 'dureza' => 'Límite de dureza y escala (HBW, HRC, HV…)',
                'notas' => 'Excepciones de la norma (opcional)',
            ]],
        ];
        foreach ($tablas as &$tabla) {
            $tabla['columnas'] += self::aplicabilidad();
        }

        return $tablas;
    }

    public static function reglas(): array
    {
        $reglas = [
            'Tablas_Adicionales' => 'sometimes|array:tratamiento,mecanica,otras',
            'Tablas_Adicionales.otras' => 'sometimes|array|max:10',
            'Tablas_Adicionales.otras.*' => 'array:titulo,columnas,filas',
            'Tablas_Adicionales.otras.*.titulo' => 'required|string|max:255',
            'Tablas_Adicionales.otras.*.columnas' => 'required|array|min:1|max:12',
            'Tablas_Adicionales.otras.*.columnas.*' => 'required|string|max:255',
            'Tablas_Adicionales.otras.*.filas' => 'sometimes|array|max:25',
            'Tablas_Adicionales.otras.*.filas.*' => 'array|max:12',
            'Tablas_Adicionales.otras.*.filas.*.*' => 'nullable|string|max:2000',
        ];
        foreach (self::definiciones() as $clave => $config) {
            $reglas['Tablas_Adicionales.'.$clave] = 'sometimes|array|max:25';
            $reglas['Tablas_Adicionales.'.$clave.'.*'] = 'array:'.implode(',', array_keys($config['columnas'])).($clave === 'mecanica' ? ',tension' : '');
            foreach ($config['columnas'] as $campo => $titulo) {
                $reglas['Tablas_Adicionales.'.$clave.'.*.'.$campo] = 'nullable|string|max:2000';
            }
        }
        $reglas['Tablas_Adicionales.mecanica.*.tension'] = 'nullable|string|max:2000';

        return $reglas;
    }

    public static function separarTension(array $fila): array
    {
        if (array_key_exists('tension_min', $fila) || array_key_exists('tension_max', $fila)) return $fila;
        $texto = trim((string) ($fila['tension'] ?? ''));
        $fila['tension_min'] = $texto;
        $fila['tension_max'] = '';
        $numero = '\d+(?:[.,]\d+)?';
        if (preg_match('/^('.$numero.')\s*[-–—]\s*('.$numero.')\s*\[\s*('.$numero.')\s*[-–—]\s*('.$numero.')\s*\]$/u', $texto, $m)) {
            $fila['tension_min'] = $m[1].' ['.$m[3].']';
            $fila['tension_max'] = $m[2].' ['.$m[4].']';
        } elseif (preg_match('/^('.$numero.')\s*[-–—]\s*('.$numero.')\s*(ksi|MPa)?$/iu', $texto, $m)) {
            $unidad = empty($m[3]) ? '' : ' '.$m[3];
            $fila['tension_min'] = $m[1].$unidad;
            $fila['tension_max'] = $m[2].$unidad;
        }
        return $fila;
    }

    public static function limpiar(array $tablas): array
    {
        $salida = [];
        if (array_key_exists('otras', $tablas)) {
            $salida['otras'] = array_values($tablas['otras']);
        }
        foreach (self::definiciones() as $clave => $config) {
            if (! array_key_exists($clave, $tablas)) {
                continue;
            }
            $salida[$clave] = array_values(array_filter(array_map(function ($fila) {
                return array_map(fn ($valor) => trim((string) ($valor ?? '')), $fila);
            }, $tablas[$clave]), fn ($fila) => count(array_filter($fila, fn ($valor) => $valor !== '')) > 0));
        }

        return $salida;
    }
}
