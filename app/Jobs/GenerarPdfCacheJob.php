<?php

namespace App\Jobs;

use App\Services\ServicioPdfCache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

/**
 * Genera un PDF fuera del request y lo deja en la cache compartida.
 * Acepta un nombre de ruta o un par controlador/metodo de la whitelist.
 */
class GenerarPdfCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 360;

    public function __construct(
        public readonly int $reporteId,
        public readonly ?string $routeName = null,
        public readonly ?string $controllerClass = null,
        public readonly ?string $controllerMethod = null
    ) {
        $this->onQueue('pdf');
    }

    public function handle(): void
    {
        if (ServicioPdfCache::existe($this->reporteId)) {
            ServicioPdfCache::limpiarPendiente($this->reporteId);
            return;
        }

        if ($this->routeName) {
            $ruta = app('router')->getRoutes()->getByName($this->routeName);

            if (!$ruta) {
                ServicioPdfCache::limpiarPendiente($this->reporteId);
                Log::error('PdfCache: ruta no encontrada', ['ruta' => $this->routeName]);
                return;
            }

            $destino = [$ruta->getController(), $ruta->getActionMethod()];
        } else {
            $destino = [app($this->controllerClass), $this->controllerMethod];
        }

        if (!$destino[0] || !$destino[1]) {
            ServicioPdfCache::limpiarPendiente($this->reporteId);
            Log::error('PdfCache: generador no resuelto', ['reporte' => $this->reporteId]);
            return;
        }

        $nivel = ob_get_level();
        $origen = $this->routeName ?: ($this->controllerClass . '::' . $this->controllerMethod);

        try {
            ob_start();

            $respuesta = app()->call($destino, ['id' => $this->reporteId]);
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

            if (!ServicioPdfCache::guardar($this->reporteId, $contenido)) {
                Log::error('PdfCache: salida no valida', [
                    'origen' => $origen,
                    'reporte' => $this->reporteId,
                    'bytes' => strlen($contenido),
                ]);
            }
        } catch (Throwable $e) {
            while (ob_get_level() > $nivel) {
                ob_end_clean();
            }

            Log::error('PdfCache: fallo al generar', [
                'origen' => $origen,
                'reporte' => $this->reporteId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        } finally {
            ServicioPdfCache::limpiarPendiente($this->reporteId);
        }
    }
}
