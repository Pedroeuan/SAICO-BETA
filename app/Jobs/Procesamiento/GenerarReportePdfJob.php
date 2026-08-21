<?php

namespace App\Jobs\Procesamiento;

use App\Http\Controllers\Reporte\IM\FOR_PIMP_03_B_01Controller;
use App\Http\Controllers\Reporte\IM\FOR_PIMP_04_02Controller;
use App\Http\Controllers\Reporte\IM\FOR_PIMP_04_03Controller;
use App\Http\Controllers\Reporte\IM\FOR_PIMP_05_B_01Controller;
use App\Http\Controllers\Reporte\IM\FOR_PIMP_06_B_01Controller;
use App\Models\Procesamiento\TrabajoProcesamiento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerarReportePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, TrabajoPesado;

    /** Lista cerrada: el navegador nunca puede indicar una clase o metodo arbitrario. */
    public const FORMATOS = [
        '03_B_01' => [FOR_PIMP_03_B_01Controller::class, 'FOR_PIMP_03_B_01'],
        '04_02' => [FOR_PIMP_04_02Controller::class, 'FOR_PIMP_04_02'],
        '04_03' => [FOR_PIMP_04_03Controller::class, 'FOR_PIMP_04_03'],
        '05_B_01' => [FOR_PIMP_05_B_01Controller::class, 'FOR_PIMP_05_B_01'],
        '06_B_01' => [FOR_PIMP_06_B_01Controller::class, 'FOR_PIMP_06_B_01'],
    ];

    // El margen evita agotar intentos mientras otro proceso posee el candado global.
    public int $tries = 50;
    public int $timeout = 360;
    public bool $failOnTimeout = true;

    public function __construct(public readonly string $trabajoId)
    {
        $this->onQueue('pdf');
    }

    /** Ejecuta el generador existente y guarda su salida fuera del directorio publico. */
    public function handle(): void
    {
        $trabajo = TrabajoProcesamiento::findOrFail($this->trabajoId);
        $trabajo->marcarProcesando('Generando reporte PDF...');

        try {
            $formato = (string) data_get($trabajo->contexto, 'formato');
            [$clase, $metodo] = self::FORMATOS[$formato] ?? throw new \RuntimeException('Formato PDF no permitido.');
            $parametros = [
                'id' => (int) data_get($trabajo->contexto, 'reporte_id'),
            ];

            // El parametro adicional se envia unicamente al generador bilingue;
            // asi no se modifican las firmas de los otros controladores PDF.
            if ($formato === '04_03') {
                $idioma = strtolower((string) data_get($trabajo->contexto, 'idioma', 'es'));
                $parametros['idioma'] = in_array($idioma, ['es', 'en'], true) ? $idioma : 'es';
            }

            $respuesta = app()->call([app($clase), $metodo], $parametros);
            $contenido = method_exists($respuesta, 'getContent') ? $respuesta->getContent() : null;
            if (!is_string($contenido) || !str_starts_with($contenido, '%PDF')) {
                throw new \RuntimeException('El generador no devolvio un documento PDF valido.');
            }

            $ruta = "procesamientos/{$trabajo->usuario_id}/{$trabajo->id}/reporte.pdf";
            Storage::disk('local')->put($ruta, $contenido);
            $trabajo->marcarCompletado([
                // Esta ruta solo se interpreta en servidor; estado() la reemplaza por una URL autorizada.
                'ruta_pdf' => Storage::disk('local')->path($ruta),
            ], 'Reporte generado correctamente.');
        } catch (Throwable $error) {
            Log::error('Fallo la generacion del reporte PDF en cola.', [
                'trabajo_id' => $trabajo->id,
                'usuario_id' => $trabajo->usuario_id,
                'error' => $error->getMessage(),
            ]);
            $trabajo->marcarError($error, 'No fue posible generar el reporte PDF.');
            $this->fail($error);
        }
    }

    /** Registra timeout o agotamiento de reintentos con un mensaje controlado. */
    public function failed(?Throwable $error): void
    {
        $trabajo = TrabajoProcesamiento::find($this->trabajoId);
        if ($trabajo && $trabajo->estado !== TrabajoProcesamiento::ESTADO_COMPLETADO) {
            $trabajo->marcarError($error?->getMessage() ?? 'El worker termino sin respuesta.', 'No fue posible generar el reporte PDF.');
        }
    }
}
