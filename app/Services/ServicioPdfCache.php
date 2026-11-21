<?php

namespace App\Services;

use Illuminate\Http\Request;
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

    /**
     * Descarta el PDF vigente de un reporte para que la proxima peticion lo regenere.
     * Se llama cuando cambian el reporte o cualquiera de sus tablas relacionadas.
     */
    public static function invalidar(int $reporteId): void
    {
        $ruta = self::ruta($reporteId);

        if (file_exists($ruta)) {
            @unlink($ruta);
        }

        $temporal = glob($ruta . '.tmp.*') ?: [];

        foreach ($temporal as $archivo) {
            @unlink($archivo);
        }

        self::limpiarPendiente($reporteId);
    }

    /**
     * URL usada por la vista de espera: conserva los demas parametros pero quita
     * `fresh` para que el polling no invalide y reencole indefinidamente.
     */
    public static function urlPolling(Request $request): string
    {
        $query = $request->query();
        unset($query['fresh']);

        return $request->url() . ($query ? '?' . http_build_query($query) : '');
    }
}
