<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Normas_IM\Normas_IM;

/** Comparte la captura de referencias del 04_02 con el 04_03. */
class ReferenciasMecanicasIM
{
    /** ASTM A370-20, tabla 3: Brinell (HB) => tension aproximada (ksi).
     * Solo pares publicados con tension; las filas inferiores sin tension no se extrapolan.
     */
    public const A370_TABLA3 = [
        116 => 56, 117 => 57, 119 => 58, 121 => 59, 123 => 60, 125 => 61,
        127 => 62, 130 => 63, 132 => 64, 135 => 65, 137 => 66, 139 => 67,
        141 => 68, 144 => 69, 147 => 70, 150 => 72, 153 => 73, 156 => 77,
        159 => 80, 162 => 81, 165 => 82, 169 => 83, 172 => 84, 176 => 86,
        180 => 88, 185 => 89, 190 => 90, 195 => 92, 200 => 94, 205 => 98,
        210 => 100, 216 => 102, 222 => 104, 228 => 109, 234 => 114, 240 => 116,
    ];

    // Formula en PSI de la hoja FORMULAS del Excel proporcionado; no procede de la tabla 3.
    public const CEDENCIA_INTERCEPTO = -13154.92;
    public const CEDENCIA_PENDIENTE = 417.1285;
    // Criterio solicitado por el tecnico para 100..115 HB; no es un valor de la tabla 3.
    public const FACTOR_TENSION_100_115_PSI = 500;

    public function calcularDesdeBrinell(float $promedio): array
    {
        $hb = (int) round($promedio, 0, PHP_ROUND_HALF_UP);
        $resultado = ['brinell' => $hb, 'estado' => 'fuera_rango', 'tension' => null,
            'cedencia' => null, 'puntos' => []];
        if (!is_finite($promedio) || $hb < 100 || $hb > 240) return $resultado;
        if ($hb <= 115) {
            $tension = self::FACTOR_TENSION_100_115_PSI * $hb / 1000;
            $resultado['estado'] = 'formula_tecnico';
        } elseif (isset(self::A370_TABLA3[$hb])) {
            $tension = self::A370_TABLA3[$hb];
            $resultado['estado'] = 'tabla';
            $resultado['puntos'] = [[$hb, $tension]];
        } else {
            $anterior = null;
            foreach (self::A370_TABLA3 as $dureza => $ksi) {
                if ($dureza > $hb) {
                    [$h0, $t0] = $anterior;
                    $tension = $t0 + ($ksi - $t0) / ($dureza - $h0) * ($hb - $h0);
                    $resultado['estado'] = 'interpolacion';
                    $resultado['puntos'] = [[$h0, $t0], [$dureza, $ksi]];
                    break;
                }
                $anterior = [$dureza, $ksi];
            }
        }
        $resultado['tension'] = number_format($tension, 3, '.', '');
        $resultado['cedencia'] = number_format((self::CEDENCIA_INTERCEPTO + self::CEDENCIA_PENDIENTE * $hb) / 1000, 3, '.', '');
        return $resultado;
    }

    /** El servidor recalcula: los resultados automaticos enviados por el navegador no son autoridad. */
    public function guardarConversionDureza(Request $request, array &$equipo): void
    {
        $request->validate(['Datos_Equipo.METODO_CALCULO_DUREZA' => 'nullable|in:a370_tabla3,manual']);
        $metodo = $request->input('Datos_Equipo.METODO_CALCULO_DUREZA', 'manual') ?: 'manual';
        $equipo['METODO_CALCULO_DUREZA'] = $metodo;
        unset($equipo['CALCULO_DUREZA']);
        if ($metodo !== 'a370_tabla3') return;
        $promedio = (string) ($equipo['PROMEDIO_DUREZA'] ?? '');
        $resultado = is_numeric($promedio) ? $this->calcularDesdeBrinell((float) $promedio)
            : ['brinell' => null, 'estado' => 'sin_lecturas', 'tension' => null, 'cedencia' => null, 'puntos' => []];
        $equipo['RESISTENCIA_TENSION'] = $resultado['tension'] ?? '---';
        $equipo['RESISTENCIA_CEDENCIA'] = $resultado['cedencia'] ?? '---';
        $formulaTecnico = $resultado['estado'] === 'formula_tecnico';
        $equipo['CALCULO_DUREZA'] = $resultado + ['norma_tension' => $formulaTecnico ? null : 'ASTM A370-20',
            'tabla' => $formulaTecnico ? null : 3,
            'formula_tension' => $formulaTecnico ? '500 * HB / 1000' : null,
            'origen_cedencia' => 'CALCULOS DE TENSION Y CEDENCIA.xlsx, FORMULAS, PSI / 1000'];
    }

    public function guardar(Request $request, array &$equipo): void
    {
        $request->validate(['Modo_Referencias_0402' => 'nullable|in:automatico,manual']);
        $equipo['MODO_REFERENCIAS_0402'] = $request->input('Modo_Referencias_0402', 'automatico');
        if ($equipo['MODO_REFERENCIAS_0402'] === 'manual') {
            $request->validate(['Referencias_Manuales_0402' => 'required|string|max:100000']);
            $equipo['NORMAS_REFERENCIA_COINCIDENCIAS'] = app(\App\Services\ReferenciasManuales0402::class)->validar($request->input('Referencias_Manuales_0402'));
            foreach (['NORMA_REFERENCIA', 'DUREZA_BRINELL_MAX', 'RESISTENCIA_TENSION_MIN', 'RESISTENCIA_CEDENCIA_ESPECIFICADA', 'RESISTENCIA_TENSION_MAX'] as $campo) $equipo[$campo] = '';
            return;
        }
        $request->validate([
            'Referencias_Seleccionadas_0402' => 'nullable|string|max:10000',
            'Escalas_Referencias_0402' => 'nullable|array|max:100',
            'Escalas_Referencias_0402.*' => 'nullable|in:HB,HBW,HRC,HRB,HV',
            'Composiciones_Referencias_0402' => 'nullable|array|max:100',
            'Composiciones_Referencias_0402.*' => 'nullable|string|max:30',
        ]);
        $equipo['NORMAS_REFERENCIA_COINCIDENCIAS'] = \App\Services\SeleccionReferencias0402::filtrar(
            app(\App\Services\CoincidenciasMecanicas0402::class)->catalogo(Normas_IM::all()),
            $request->input('Referencias_Seleccionadas_0402', '[]') ?? '[]'
        );
        foreach ($equipo['NORMAS_REFERENCIA_COINCIDENCIAS'] as &$referencia) {
            $clave = $referencia['idnormas_im'].':'.$referencia['fila'];
            $escala = $request->input('Escalas_Referencias_0402', [])[$clave] ?? '';
            $referencia['escala_dureza'] = $escala;
            if ($escala && ($referencia['dureza_max'] ?? '') !== '') {
                $referencia['dureza_max'] = trim(preg_replace('/\b(?:HBW|HB|HRC|HRB|HV)\b/i', '', (string) $referencia['dureza_max'])).' '.$escala;
            }
        }
        unset($referencia);
        $equipo['NORMAS_REFERENCIA_COINCIDENCIAS'] = \App\Services\ComposicionesReferencias0402::seleccionar(
            $equipo['NORMAS_REFERENCIA_COINCIDENCIAS'], $request->input('Composiciones_Referencias_0402', [])
        );
        $request->attributes->set('referencias_quimicas_0402', $equipo['NORMAS_REFERENCIA_COINCIDENCIAS']);
        foreach (['NORMA_REFERENCIA', 'DUREZA_BRINELL_MAX', 'RESISTENCIA_TENSION_MIN', 'RESISTENCIA_CEDENCIA_ESPECIFICADA', 'RESISTENCIA_TENSION_MAX'] as $campo) $equipo[$campo] = '';
    }
}
