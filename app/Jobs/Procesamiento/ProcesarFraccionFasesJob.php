<?php

namespace App\Jobs\Procesamiento;

use App\Models\Procesamiento\TrabajoProcesamiento;
use App\Services\ServicioAnalisisImagenImageJ;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcesarFraccionFasesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, TrabajoPesado;

    // El numero alto permite esperar el candado; un fallo real se marca terminal en handle().
    public int $tries = 50;
    public int $timeout = 360;
    public bool $failOnTimeout = true;

    public function __construct(public readonly string $trabajoId)
    {
        $this->onQueue('fiji');
    }

    /** Ejecuta Fiji con una copia privada y durable de la imagen cargada. */
    public function handle(ServicioAnalisisImagenImageJ $servicio): void
    {
        $trabajo = TrabajoProcesamiento::findOrFail($this->trabajoId);
        $trabajo->marcarProcesando('Procesando imagen con Fiji...');
        $contexto = $trabajo->contexto ?? [];
        $ruta = (string) ($contexto['ruta_entrada'] ?? '');

        try {
            if ($ruta === '' || !Storage::disk('local')->exists($ruta)) {
                throw new \RuntimeException('La imagen temporal del analisis no esta disponible.');
            }

            // El servicio mueve su UploadedFile; se procesa una copia para permitir un reintento real.
            $rutaIntento = dirname($ruta) . '/intento-' . $this->attempts() . '.' . pathinfo($ruta, PATHINFO_EXTENSION);
            Storage::disk('local')->copy($ruta, $rutaIntento);
            $rutaAbsoluta = Storage::disk('local')->path($rutaIntento);
            // test=true evita que Symfony intente validar una subida HTTP ya movida.
            $archivo = new UploadedFile(
                $rutaAbsoluta,
                (string) ($contexto['nombre_original'] ?? basename($ruta)),
                $contexto['mime'] ?? null,
                null,
                true
            );

            $resultado = $servicio->procesarFraccionFases(
                $archivo,
                (int) $contexto['umbral_minimo'],
                (int) $contexto['umbral_maximo'],
                (string) $contexto['fase_seleccionada'],
                (int) $trabajo->usuario_id
            );

            $trabajo->marcarCompletado(['analisis' => $resultado], 'Imagen procesada correctamente.');
            Storage::disk('local')->deleteDirectory(dirname($ruta));
        } catch (Throwable $error) {
            $trabajo->marcarError($error, 'Fiji/ImageJ no pudo procesar la imagen.');
            Log::error('Fallo el trabajo de fraccion de fases.', [
                'trabajo_id' => $trabajo->id,
                'usuario_id' => $trabajo->usuario_id,
                'error' => $error->getMessage(),
            ]);
            $this->fail($error);
        }
    }

    /** Asegura un estado terminal si la cola agota reintentos o tiempo. */
    public function failed(?Throwable $error): void
    {
        $trabajo = TrabajoProcesamiento::find($this->trabajoId);
        if ($trabajo && $trabajo->estado !== TrabajoProcesamiento::ESTADO_COMPLETADO) {
            $trabajo->marcarError($error?->getMessage() ?? 'El worker termino sin respuesta.', 'Fiji/ImageJ no pudo procesar la imagen.');
            $ruta = (string) data_get($trabajo->contexto, 'ruta_entrada');
            Storage::disk('local')->deleteDirectory(dirname($ruta));
        }
    }
}
