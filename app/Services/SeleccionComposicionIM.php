<?php

namespace App\Services;

use App\Models\Normas_IM\Normas_IM;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SeleccionComposicionIM
{
    public static function opciones(Normas_IM $norma): array
    {
        $opciones = [];
        foreach (($norma->Tablas_Adicionales['quimica_configuraciones'] ?? []) as $indice => $config) {
            $filas = [];
            foreach ($config['elementos'] as $elemento) {
                $min = $elemento['minimo'] ?? null; $max = $elemento['maximo'] ?? null;
                $criterio = $min !== null && $max !== null ? "{$min}–{$max} %" : ($min !== null ? "$min % mínimo" : ($max !== null ? "$max % máximo" : 'Sin límite establecido'));
                if (!empty($elemento['criterio'])) $criterio = $criterio === 'Sin límite establecido' ? $elemento['criterio'] : $criterio.'; '.$elemento['criterio'];
                if (!empty($elemento['condicion'])) $criterio .= '; '.$elemento['condicion'];
                $filas[] = ['Elemento' => $elemento['elemento'], 'Composicion' => $criterio, 'Aplicabilidad' => array_intersect_key($config, array_flip(['grado', 'producto', 'ancho', 'espesor', 'condiciones']))];
            }
            $opciones[] = ['configuracion' => $indice, 'Variable' => implode(' / ', array_filter([$config['grado'] ?? '', $config['producto'] ?? '', $config['ancho'] ?? '', $config['espesor'] ?? ''])) ?: 'General', 'Tabla' => $filas, 'Notas_Tecnico' => trim((string) ($config['condiciones'] ?? '')), 'Observaciones' => trim(($norma->Observaciones ?? '').' '.($config['condiciones'] ?? ''))];
        }

        return $opciones;
    }

    public static function resolver(Normas_IM $norma, Request $request, ?array $historica = null): array
    {
        $indice = $request->input('Norma_IM.configuracion', $request->input('configuracion'));
        if ($indice === null || $indice === '') return ['Variable' => $norma->Variable, 'Tabla' => json_decode($norma->Tabla, true) ?: [], 'Observaciones' => $norma->Observaciones];
        if ($historica && (int) ($historica['idnormas_im'] ?? 0) === (int) $norma->idnormas_im && isset($historica['configuracion']) && (string) $historica['configuracion'] === (string) $indice && !$request->boolean('Norma_IM.configuracion_cambiada')) {
            return ['Variable' => $historica['Variable'] ?? '', 'Tabla' => $historica['Tabla'] ?? [], 'Observaciones' => $historica['Observaciones'] ?? ''];
        }
        foreach (self::opciones($norma) as $opcion) if ((string) $opcion['configuracion'] === (string) $indice) return $opcion;
        throw ValidationException::withMessages(['Norma_IM.configuracion' => 'La configuración seleccionada no pertenece a esta norma.']);
    }
}
