<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Cache central de PDFs generados, compartida por tecnico, portal de clientes y QR.
 * La clave es el idReportes: un reporte tiene un unico PDF vigente.
 */
class ServicioPdfCache
{
    public static function ruta(int $reporteId): string
    {
        $directorio = storage_path('app/public/Reportes/PDF_CACHE');

        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        return $directorio . DIRECTORY_SEPARATOR . $reporteId . '.pdf';
    }

    public static function existe(int $reporteId): bool
    {
        $ruta = self::ruta($reporteId);

        return file_exists($ruta) && filesize($ruta) > 1024;
    }

    public static function respuesta(int $reporteId)
    {
        return response()->file(self::ruta($reporteId), [
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public static function guardar(int $reporteId, string $contenido): bool
    {
        if (!str_starts_with(ltrim($contenido), '%PDF')) {
            return false;
        }

        $ruta = self::ruta($reporteId);
        $temporal = $ruta . '.tmp.' . getmypid();

        if (file_put_contents($temporal, $contenido) === false) {
            return false;
        }

        return rename($temporal, $ruta);
    }

    /** Evita encolar dos veces el mismo PDF mientras el primero se esta generando. */
    public static function marcarPendiente(int $reporteId): bool
    {
        return Cache::add("pdf_cache_pendiente_{$reporteId}", 1, now()->addMinutes(15));
    }

    public static function limpiarPendiente(int $reporteId): void
    {
        Cache::forget("pdf_cache_pendiente_{$reporteId}");
    }
}
