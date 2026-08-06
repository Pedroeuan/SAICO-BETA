<?php

namespace App\Jobs\Procesamiento;

use App\Models\Normas_IM\Normas_IM;
use App\Models\Procesamiento\TrabajoProcesamiento;
use App\Services\ServicioAnalisisColumnasPdfXrf;
use App\Services\ServicioAnalisisPdfXrf;
use App\Services\ServicioCapturaColumnasPdfXrf;
use App\Services\ServicioImagenesPdfXrf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcesarXrfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, TrabajoPesado;

    // Los intentos adicionales son para esperar el candado, no para repetir un PDF invalido.
    public int $tries = 50;
    public int $timeout = 360;
    public bool $failOnTimeout = true;

    public function __construct(public readonly string $trabajoId)
    {
        $this->onQueue('xrf');
    }

    /** Resuelve el modo de XRF sin duplicar workers por formato. */
    public function handle(
        ServicioAnalisisPdfXrf $analisisMultiple,
        ServicioImagenesPdfXrf $imagenes,
        ServicioAnalisisColumnasPdfXrf $analisisColumnas,
        ServicioCapturaColumnasPdfXrf $capturaColumnas
    ): void {
        $trabajo = TrabajoProcesamiento::findOrFail($this->trabajoId);
        $trabajo->marcarProcesando('Procesando PDF XRF...');

        try {
            $archivos = $this->crearArchivos($trabajo);
            $resultado = $trabajo->tipo === 'xrf_multiple'
                ? $this->procesarMultiple($trabajo, $archivos, $analisisMultiple, $imagenes)
                : $this->procesarColumnas($trabajo, $archivos[0], $analisisColumnas, $capturaColumnas);
            $trabajo->marcarCompletado($resultado, 'PDF XRF procesado correctamente.');
            // Las entradas se conservan hasta guardar el reporte o vencer expira_at;
            // esto permite recuperarlas cuando el navegador pierde su FileList al recargar.
        } catch (Throwable $error) {
            Log::error('Fallo el procesamiento XRF en cola.', [
                'trabajo_id' => $trabajo->id,
                'usuario_id' => $trabajo->usuario_id,
                'error' => $error->getMessage(),
            ]);
            $trabajo->marcarError($error, 'No fue posible procesar el PDF XRF.');
            $this->fail($error);
        }
    }

    /** Reconstruye UploadedFile confiables desde las copias privadas. */
    private function crearArchivos(TrabajoProcesamiento $trabajo): array
    {
        return array_map(static function (array $entrada): UploadedFile {
            $ruta = (string) ($entrada['ruta'] ?? '');
            if ($ruta === '' || !Storage::disk('local')->exists($ruta)) {
                throw new \RuntimeException('Un PDF temporal ya no esta disponible.');
            }
            return new UploadedFile(
                Storage::disk('local')->path($ruta),
                (string) ($entrada['nombre'] ?? 'analisis.pdf'),
                $entrada['mime'] ?? 'application/pdf',
                null,
                true
            );
        }, $trabajo->contexto['entradas'] ?? []);
    }

    /** Reproduce lectura, validacion de norma, promedio y seis recortes. */
    private function procesarMultiple(
        TrabajoProcesamiento $trabajo,
        array $archivos,
        ServicioAnalisisPdfXrf $service,
        ServicioImagenesPdfXrf $imageService
    ): array {
        $norma = Normas_IM::findOrFail((int) $trabajo->contexto['idnormas_im']);
        $filas = json_decode($norma->Tabla, true);
        $filas = is_array($filas) ? $filas : [];
        $elementos = array_values(array_filter(array_map(
            static fn ($fila) => is_array($fila) ? ($fila['Elemento'] ?? null) : null,
            $filas
        )));
        $analisis = array_map(static fn (UploadedFile $archivo) => $service->parseUploadedFile($archivo), $archivos);
        $service->assertCompatibleWithNorm($analisis, (string) $norma->Nombre_Espe, (string) $norma->Variable);

        $recortes = [];
        foreach (array_slice($archivos, 0, 3) as $indice => $archivo) {
            $imagenes = $imageService->generateCrops($archivo);
            $recortes[] = [
                'disparo' => $indice + 1,
                'archivo' => $archivo->getClientOriginalName(),
                'daily_id' => $analisis[$indice]['metadatos']['daily_id'] ?? null,
                'tabla_elementos' => $imagenes['tabla_elementos'],
                'grafica_espectro' => $imagenes['grafica_espectro'],
            ];
        }

        return [
            'analisis' => $analisis,
            'promedios' => $service->averageForElements($analisis, $elementos),
            'recortes_disparos' => $recortes,
        ];
    }

    /** Detecta columnas o calcula el promedio y genera la captura seleccionada. */
    private function procesarColumnas(
        TrabajoProcesamiento $trabajo,
        UploadedFile $archivo,
        ServicioAnalisisColumnasPdfXrf $service,
        ServicioCapturaColumnasPdfXrf $captureService
    ): array {
        $analisis = $service->parseUploadedFile($archivo);
        $base = [
            'archivo' => $archivo->getClientOriginalName(),
            'paginas' => $analisis['paginas'] ?? null,
            'columnas_disponibles' => $analisis['columnas'] ?? [],
        ];
        $solicitadas = array_values(array_unique(array_map('intval', $trabajo->contexto['columnas'] ?? [])));
        if ($solicitadas === []) {
            return array_merge($base, ['solo_deteccion' => true]);
        }

        $disponibles = array_values(array_unique(array_map('intval', $analisis['columnas'] ?? [])));
        $seleccionadas = array_slice(array_values(array_intersect($solicitadas, $disponibles)), 0, 3);
        if ($seleccionadas === []) {
            $seleccionadas = array_slice($disponibles, 0, 3);
        }

        return array_merge($base, [
            'columnas_seleccionadas' => $seleccionadas,
            'resultados' => $service->calculateForColumns($analisis, $seleccionadas),
            'captura' => $captureService->generate($archivo, $seleccionadas, true),
        ]);
    }

    /** Deja el UUID en error si el worker termina por timeout o agota reintentos. */
    public function failed(?Throwable $error): void
    {
        $trabajo = TrabajoProcesamiento::find($this->trabajoId);
        if ($trabajo && $trabajo->estado !== TrabajoProcesamiento::ESTADO_COMPLETADO) {
            $trabajo->marcarError($error?->getMessage() ?? 'El worker termino sin respuesta.', 'No fue posible procesar el PDF XRF.');
            Storage::disk('local')->deleteDirectory("procesamientos/{$trabajo->usuario_id}/{$trabajo->id}");
        }
    }
}
