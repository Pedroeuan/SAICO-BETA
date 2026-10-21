<?php

namespace App\Services;

use App\Models\Normas_IM\Normas_IM;
use Illuminate\Validation\ValidationException;

/** Selección exclusiva de composición química: no depende de propiedades mecánicas. */
class ReferenciasQuimicas0501
{
    public static function catalogo(iterable $normas): array
    {
        $opciones = [];
        foreach ($normas as $norma) {
            $configuraciones = SeleccionComposicionIM::opciones($norma);
            if (!$configuraciones) {
                $configuraciones = [['configuracion' => 'base', 'Variable' => $norma->Variable,
                    'Tabla' => json_decode($norma->Tabla, true) ?: [], 'Observaciones' => $norma->Observaciones]];
            }
            foreach ($configuraciones as $configuracion) {
                $filas = array_values(array_filter($configuracion['Tabla'] ?? [], static fn ($fila) => is_array($fila) && trim((string) ($fila['Elemento'] ?? '')) !== ''));
                if (!$filas) continue;
                $opciones[] = [
                    'clave' => $norma->idnormas_im . ':' . $configuracion['configuracion'],
                    'idnormas_im' => (int) $norma->idnormas_im,
                    'norma' => trim($norma->Nombre_Espe . ' / ' . ($configuracion['Variable'] ?? '')),
                    'Nombre_Espe' => $norma->Nombre_Espe,
                    'composicion_seleccionada' => array_merge($configuracion, ['Tabla' => $filas]),
                ];
            }
        }
        return $opciones;
    }

    /** Conserva la copia histórica al editar; las nuevas selecciones se verifican contra el catálogo. */
    public static function resolver(array $claves, ?array $historica): array
    {
        if (count($claves) > 100) throw ValidationException::withMessages(['Norma_IM.Seleccion_Quimica' => 'Seleccione como máximo 100 referencias.']);
        $catalogo = array_column(self::catalogo(Normas_IM::all()), null, 'clave');
        $guardadas = array_column($historica['Referencias_Quimicas'] ?? [], null, 'clave');
        if (!empty($historica['idnormas_im']) && !array_key_exists('Seleccion_Quimica', $historica)) {
            $claveAnterior = $historica['idnormas_im'] . ':base';
            $guardadas[$claveAnterior] = ['clave' => $claveAnterior, 'idnormas_im' => (int) $historica['idnormas_im'],
                'norma' => $historica['Nombre_Espe'] ?? '', 'composicion_seleccionada' => $historica];
        }
        $seleccion = [];
        foreach (array_unique($claves) as $clave) {
            $referencia = $guardadas[$clave] ?? $catalogo[$clave] ?? null;
            if (!$referencia) throw ValidationException::withMessages(['Norma_IM.Seleccion_Quimica' => 'Una referencia química seleccionada ya no está disponible.']);
            $seleccion[] = $referencia;
        }
        return $seleccion;
    }
}
