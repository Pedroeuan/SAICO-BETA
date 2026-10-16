<?php

namespace App\Services;

class SumaElementos0402
{
    public static function calcular(string $formula, array $valores): ?string
    {
        if (!preg_match('/^[A-Za-z]{1,2}(?:\s*\+\s*[A-Za-z]{1,2})+$/', trim($formula))) return null;
        $valores = array_change_key_case($valores, CASE_LOWER);
        $total = 0;
        $limite = false;
        foreach (preg_split('/\s*\+\s*/', trim($formula)) as $elemento) {
            $valor = trim(str_replace(',', '.', (string) ($valores[strtolower($elemento)] ?? '')));
            if (!preg_match('/^(\[C<\])?(\d+(?:\.\d+)?)$/', $valor, $m)) return null;
            $numero = (float) $m[2];
            if ($numero > 100) return null;
            $total += (int) round($numero * 10000);
            $limite = $limite || !empty($m[1]);
        }
        return ($limite ? '[C<]' : '').number_format($total / 10000, 4, '.', '');
    }

    public static function completar(array $filas, array $resultadosPdf, array $manuales = [], array $sumasManuales = []): array
    {
        $valores = $manuales;
        $service = app(ServicioAnalisisColumnasPdfXrf::class);
        foreach ($resultadosPdf as $elemento => $resultado) {
            $resuelto = $service->resolveAverage($resultado, (string) ($manuales[strtolower($elemento)] ?? ''));
            $valores[strtolower($elemento)] = $resuelto['promedio'];
        }
        foreach ($filas as $fila) {
            if (preg_match('/^[A-Za-z]{1,2}$/', trim($fila['Elemento']))) $valores[strtolower(trim($fila['Elemento']))] = $fila['Promedio'];
        }
        foreach ($filas as &$fila) {
            if (!empty($sumasManuales[trim($fila['Elemento'])])) {
                $fila['Origen'] = trim((string) $fila['Promedio']) === '' ? 'pendiente_manual' : 'manual';
                continue;
            }
            $suma = self::calcular($fila['Elemento'], $valores);
            if ($suma !== null) {
                $fila['Promedio'] = $suma;
                $fila['Origen'] = 'calculado';
            } elseif (str_contains($fila['Elemento'], '+') && $fila['Origen'] === 'calculado') {
                $fila['Promedio'] = ''; $fila['Origen'] = 'pendiente_manual';
            }
        }
        unset($fila);
        return $filas;
    }
}
