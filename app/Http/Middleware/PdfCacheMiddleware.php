<?php

namespace App\Http\Middleware;

use App\Jobs\GenerarPdfCacheJob;
use App\Services\ServicioPdfCache;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Sirve el PDF desde cache y, si no existe, lo genera en la cola 'pdf'.
 * Solo actua sobre las rutas generadoras nombre Reporte_*.PDF.
 */
class PdfCacheMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $ruta = $request->route();
        $nombre = $ruta?->getName();

        if (!$nombre || !preg_match('/^Reporte_.*\.PDF$/', $nombre)) {
            return $next($request);
        }

        $reporteId = (int) ($ruta->parameter('id') ?? 0);

        if ($reporteId <= 0) {
            return $next($request);
        }

        // Las rutas del tecnico estan autenticadas; si no hay sesion,
        // se deja pasar para que el middleware de auth haga su trabajo.
        if (!Auth::check() && !$request->is('portal/*') && !$request->is('qr/*')) {
            return $next($request);
        }

        // Regeneracion manual: ?fresh=1 descarta el PDF vigente y encola otro.
        $fresh = $request->query('fresh') === '1';

        if ($fresh) {
            ServicioPdfCache::invalidar($reporteId);
        }

        // Modo interno usado por el job: genera y guarda, sin devolver la vista.
        if ($request->header('X-SAICO-PDF-GENERAR') === '1') {
            $nivel = ob_get_level();

            try {
                ob_start();
                $respuesta = $next($request);
                $contenido = '';

                if ($respuesta instanceof BinaryFileResponse) {
                    $archivo = $respuesta->getFile();
                    $contenido = $archivo && $archivo->isFile()
                        ? (string) file_get_contents($archivo->getPathname())
                        : '';
                } elseif (is_object($respuesta) && method_exists($respuesta, 'getContent')) {
                    $contenido = (string) $respuesta->getContent();
                }

                if ($contenido === '') {
                    $contenido = (string) ob_get_contents();
                }

                while (ob_get_level() > $nivel) {
                    ob_end_clean();
                }

                ServicioPdfCache::guardar($reporteId, $contenido);
            } finally {
                while (ob_get_level() > $nivel) {
                    ob_end_clean();
                }
                ServicioPdfCache::limpiarPendiente($reporteId);
            }

            return $respuesta ?? $next($request);
        }

        if (!$fresh && ServicioPdfCache::existe($reporteId)) {
            return ServicioPdfCache::respuesta($reporteId);
        }

        if (ServicioPdfCache::marcarPendiente($reporteId)) {
            GenerarPdfCacheJob::dispatch($reporteId, $nombre);
        }

        return response()->view('pdf.en_proceso', [
            'reporteId' => $reporteId,
            'url' => ServicioPdfCache::urlPolling($request),
        ], 202);
    }
}
