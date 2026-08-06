<?php

namespace App\Http\Controllers;

use App\Jobs\Procesamiento\ProcesarFraccionFasesJob;
use App\Models\Procesamiento\TrabajoProcesamiento;
use App\Services\ServicioAnalisisImagenImageJ;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class AnalisisImagenController extends Controller
{
    /**
     * Convierte temporalmente la imagen a 8 bits con Fiji y devuelve sus 256 frecuencias.
     * Se conserva sin cola porque el tecnico necesita este resultado para mover el umbral.
     */
    public function histograma(Request $request, ServicioAnalisisImagenImageJ $servicio): JsonResponse
    {
        $request->validate([
            'imagen' => 'required|file|mimes:jpg,jpeg,png,tif,tiff|max:25600',
        ]);

        // El histograma tambien inicia Fiji y debe respetar el mismo candado que los workers.
        $bloqueo = Cache::lock('laravel-queue-overlap:' . ProcesarFraccionFasesJob::CLAVE_BLOQUEO, 420);
        if (!$bloqueo->get()) {
            return response()->json([
                'ok' => false,
                'message' => 'Hay otro procesamiento en curso. Espere un momento y vuelva a intentarlo.',
            ], 409);
        }

        try {
            // El servicio elimina el directorio temporal cuando termina esta peticion.
            return response()->json([
                'ok' => true,
                'imagen' => $servicio->obtenerHistograma8Bit($request->file('imagen')),
            ]);
        } catch (Throwable $error) {
            Log::error('No fue posible obtener el histograma de 8 bits con ImageJ.', [
                'usuario_id' => $request->user()?->getAuthIdentifier(),
                'error' => $error->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Fiji/ImageJ no pudo preparar el histograma de la imagen.',
            ], 422);
        } finally {
            $bloqueo->release();
        }
    }

    /**
     * Encola Apply, Area Fraction y Measure para liberar la peticion web.
     * El navegador consulta despues el UUID durable del trabajo.
     */
    public function fraccionFases(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'imagen' => 'required|file|mimes:jpg,jpeg,png,tif,tiff|max:25600',
            'umbral_minimo' => 'required|integer|between:0,255|lte:umbral_maximo',
            'umbral_maximo' => 'required|integer|between:0,255|gte:umbral_minimo',
            'fase_seleccionada' => 'required|in:perlita,ferrita',
        ]);

        $usuarioId = (int) $request->user()->getAuthIdentifier();
        $trabajoId = (string) Str::uuid();
        $extension = strtolower($request->file('imagen')->getClientOriginalExtension() ?: 'img');
        $rutaEntrada = "procesamientos/{$usuarioId}/{$trabajoId}/entrada.{$extension}";

        try {
            // Una copia privada permite procesar despues de finalizar esta peticion HTTP.
            Storage::disk('local')->putFileAs(
                dirname($rutaEntrada),
                $request->file('imagen'),
                basename($rutaEntrada)
            );

            $trabajo = TrabajoProcesamiento::create([
                'id' => $trabajoId,
                'usuario_id' => $usuarioId,
                'tipo' => 'fiji_fraccion_fases',
                'estado' => TrabajoProcesamiento::ESTADO_PENDIENTE,
                'mensaje' => 'Procesando imagen con Fiji...',
                'contexto' => [
                    'ruta_entrada' => $rutaEntrada,
                    'nombre_original' => $request->file('imagen')->getClientOriginalName(),
                    'mime' => $request->file('imagen')->getMimeType(),
                    'umbral_minimo' => (int) $datos['umbral_minimo'],
                    'umbral_maximo' => (int) $datos['umbral_maximo'],
                    'fase_seleccionada' => $datos['fase_seleccionada'],
                ],
                'expira_at' => now()->addDay(),
            ]);

            ProcesarFraccionFasesJob::dispatch($trabajo->id);

            return response()->json([
                'ok' => true,
                'trabajo' => [
                    'id' => $trabajo->id,
                    'estado' => $trabajo->fresh()->estado,
                    'estado_url' => route('procesamientos.estado', $trabajo->id),
                ],
            ], 202);
        } catch (Throwable $error) {
            // Si no pudo encolarse, no se conserva una copia privada huerfana.
            Storage::disk('local')->delete($rutaEntrada);
            TrabajoProcesamiento::find($trabajoId)?->marcarError($error, 'No fue posible iniciar Fiji/ImageJ.');
            Log::error('No fue posible encolar la fraccion de fases con ImageJ.', [
                'usuario_id' => $usuarioId,
                'error' => $error->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'No fue posible iniciar Fiji/ImageJ. Vuelva a intentarlo.',
            ], 422);
        }
    }
}
