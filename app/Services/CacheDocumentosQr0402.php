<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

/** Evita convertir otra vez los anexos del QR cuando solo cambia texto. */
class CacheDocumentosQr0402
{
    public function firma(array $parametros, array $documentos): string
    {
        $archivos = [];
        foreach ($documentos as $ruta) {
            if (is_file($ruta)) {
                // Incluir el hash del contenido Y la fecha de modificación
                // para que al sobrescribir un archivo (mismo path) se invalide el caché
                $archivos[$ruta] = hash_file('sha256', $ruta) . '|' . filemtime($ruta);
            } else {
                $archivos[$ruta] = null;
            }
        }
        return hash('sha256', serialize([1, $parametros, $archivos]));
    }

    public function obtener(string $firma): ?array
    {
        $disco = Storage::disk('local');
        $ruta = "reportes_generados/04_02/qr/{$firma}.json";
        if (!$disco->exists($ruta)) return null;
        $resultado = json_decode($disco->get($ruta), true);
        if (!is_array($resultado) || empty($resultado['qr'])) return null;
        foreach (['qr', 'pdf'] as $campo) {
            if (empty($resultado[$campo])) continue;
            $archivo = preg_replace('#^storage/#', '', $resultado[$campo]);
            if (!Storage::disk('public')->exists($archivo)) return null;
            if (($resultado['_sha256'][$campo] ?? null) !== hash_file('sha256', Storage::disk('public')->path($archivo))) return null;
        }
        unset($resultado['_sha256']);
        return $resultado;
    }

    public function guardar(string $firma, array $resultado): void
    {
        foreach (['qr', 'pdf'] as $campo) {
            if (!empty($resultado[$campo])) {
                $ruta = Storage::disk('public')->path(preg_replace('#^storage/#', '', $resultado[$campo]));
                if (!is_file($ruta)) return;
                $resultado['_sha256'][$campo] = hash_file('sha256', $ruta);
            }
        }
        Storage::disk('local')->put("reportes_generados/04_02/qr/{$firma}.json", json_encode($resultado, JSON_THROW_ON_ERROR));
    }
}
