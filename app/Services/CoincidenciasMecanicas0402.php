<?php

namespace App\Services;

class CoincidenciasMecanicas0402
{
    public function catalogo(iterable $normas): array
    {
        $resultado = [];
        foreach ($normas as $norma) {
            $quimica = SeleccionComposicionIM::opciones($norma);
            if (!$quimica && !empty($norma->Tabla)) {
                $quimica = [['configuracion' => 'legacy', 'Variable' => $norma->Variable ?: 'Composición registrada', 'Tabla' => json_decode($norma->Tabla, true) ?: []]];
            }
            $mecanicas = $norma->Tablas_Adicionales['mecanica'] ?? [];
            if (!$mecanicas) {
                // Los registros anteriores pueden conservar únicamente composición química.
                // Reservar índices evita confundirlos con filas mecánicas agregadas después.
                $grados = [];
                foreach (($norma->Tablas_Adicionales['quimica_configuraciones'] ?? []) as $indice => $config) {
                    $grado = trim((string) ($config['grado'] ?? $norma->Variable));
                    $claveGrado = mb_strtolower($grado);
                    if (isset($grados[$claveGrado])) continue;
                    $grados[$claveGrado] = true;
                    $mecanicas[1000000 + $indice] = ['grado' => $grado];
                }
                if (!$mecanicas) $mecanicas[1000000] = ['grado' => $norma->Variable];
            }
            foreach ($mecanicas as $indice => $fila) {
                $fila = TablasNormasIM::separarTension($fila);
                $normalizarGrado = static fn ($grado) => preg_replace('/\s+/u', ' ', trim(preg_replace('/^grado\s+/iu', '', mb_strtolower((string) $grado))));
                $grado = $normalizarGrado(($fila['grado'] ?? '') ?: $norma->Variable);
                $delGrado = array_values(array_filter($quimica, function ($opcion) use ($norma, $grado, $normalizarGrado) {
                    $config = $norma->Tablas_Adicionales['quimica_configuraciones'][$opcion['configuracion']] ?? [];
                    return $grado !== '' && $normalizarGrado($config['grado'] ?? '') === $grado;
                }));
                $t = $this->limites((string) ($fila['tension_min'] ?? ''));
                $maximo = $this->limites((string) ($fila['tension_max'] ?? ''));
                $c = $this->limites((string) ($fila['fluencia'] ?? ''));
                $resultado[] = [
                    'idnormas_im' => $norma->idnormas_im, 'fila' => $indice,
                    'nombre_norma' => $norma->Nombre_Espe,
                    'norma' => trim($norma->Nombre_Espe.' / '.(($fila['grado'] ?? '') ?: $norma->Variable)),
                    'dureza_max' => $fila['dureza'] ?? null,
                    'composiciones' => $delGrado ?: $quimica,
                    'tension_min' => $t ? round($t[0], 4) : (($fila['tension_min'] ?? '') !== '' ? $fila['tension_min'] : null),
                    'tension_max' => $maximo ? round($maximo[0], 4) : (($fila['tension_max'] ?? '') !== '' ? $fila['tension_max'] : null),
                    'cedencia_min' => $c ? round($c[0], 4) : ($fila['fluencia'] ?? null),
                    'condiciones' => implode(' / ', array_filter(array_map(fn ($campo) => $fila[$campo] ?? '', ['producto', 'ancho', 'espesor', 'condiciones', 'notas']))),
                ];
            }
        }
        return $resultado;
    }

    private function limites(string $texto): ?array
    {
        $texto = trim(mb_strtolower(str_replace(['–', '—', '−', ','], ['-', '-', '-', '.'], $texto)));
        $factor = 1.0;
        $texto = preg_replace('/\s*(?:mínimo|minimo|mín\.?|min\.?)\s*$/u', '', $texto);
        if (preg_match('/^(.+?)\s*\[\s*\d+(?:\.\d+)?(?:\s*-\s*\d+(?:\.\d+)?)?\s*\]\s*$/u', $texto, $m)) {
            $texto = $m[1]; // Formato documentado: ksi [MPa].
        } elseif (str_contains($texto, 'mpa')) {
            $factor = 1 / 6.894757293168;
        } elseif (!str_contains($texto, 'ksi')) {
            return null; // No interpretar unidades desconocidas.
        }
        $texto = trim(str_replace(['ksi', 'mpa', 'mínimo', 'minimo', 'mín.', 'min.', 'mín', 'min'], '', $texto));
        if (!preg_match('/^(\d+(?:\.\d+)?)(?:\s*-\s*(\d+(?:\.\d+)?))?$/', $texto, $m)) return null;
        $min = (float) $m[1] * $factor;
        $max = isset($m[2]) ? (float) $m[2] * $factor : null;
        if ($max !== null && $min > $max) return null;

        return [$min, $max];
    }

    public function buscar(iterable $normas, ?float $tension, ?float $cedencia, ?float $dureza = null): array
    {
        if ($tension === null || $cedencia === null || $tension <= 0 || $cedencia <= 0) return [];
        $coincidencias = [];
        foreach ($normas as $norma) {
            foreach (($norma->Tablas_Adicionales['mecanica'] ?? []) as $indice => $fila) {
                $t = $this->limites((string) ($fila['tension'] ?? ''));
                $c = $this->limites((string) ($fila['fluencia'] ?? ''));
                if (!$t || !$c || $tension < $t[0] || ($t[1] !== null && $tension > $t[1]) || $cedencia < $c[0] || ($c[1] !== null && $cedencia > $c[1])) continue;
                $hb = null;
                $textoHb = trim((string) ($fila['dureza'] ?? ''));
                if ($dureza !== null && preg_match('/^(\d+(?:\.\d+)?)\s*(?:-|–)\s*(\d+(?:\.\d+)?)\s*(?:HBW?|máx\.?|máximo)?$/iu', $textoHb, $h)) {
                    if ($dureza < (float) $h[1] || $dureza > (float) $h[2]) continue;
                    $hb = $h[2];
                } elseif ($dureza !== null && preg_match('/^(\d+(?:\.\d+)?)\s*(?:HBW?|máx\.?|máximo)?$/iu', $textoHb, $h)) {
                    if ($dureza > (float) $h[1]) continue;
                    $hb = $h[1];
                }
                $condiciones = implode(' / ', array_filter(array_map(fn ($campo) => $fila[$campo] ?? '', ['producto', 'ancho', 'espesor', 'condiciones', 'notas'])));
                $coincidencias[] = [
                    'idnormas_im' => $norma->idnormas_im, 'fila' => $indice,
                    'norma' => trim($norma->Nombre_Espe.' / '.($fila['grado'] ?? $norma->Variable)),
                    'dureza_max' => $hb, 'tension_min' => round($t[0], 4), 'tension_max' => $t[1] !== null ? round($t[1], 4) : null,
                    'cedencia_min' => round($c[0], 4), 'condiciones' => $condiciones,
                ];
            }
        }

        return $coincidencias;
    }
}
