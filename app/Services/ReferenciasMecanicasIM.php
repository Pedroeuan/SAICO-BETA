<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Normas_IM\Normas_IM;

/** Comparte la captura de referencias del 04_02 con el 04_03. */
class ReferenciasMecanicasIM
{
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
