<?php

namespace App\Http\Controllers\Procesamiento;

use App\Http\Controllers\Controller;
use App\Jobs\Procesamiento\ProcesarXrfJob;
use App\Models\Procesamiento\TrabajoProcesamiento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ProcesamientoXrfController extends Controller
{
    /** Encola los PDF independientes utilizados por 04_03 y 06_B_01. */
    public function encolarMultiple(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'idnormas_im' => 'required|integer|exists:Normas_IM,idnormas_im',
            'Analisis_PDF' => 'required|array|min:1|max:10',
            'Analisis_PDF.*' => 'required|file|mimes:pdf|max:10240',
        ]);

        return $this->crearTrabajo(
            $request,
            'xrf_multiple',
            $request->file('Analisis_PDF', []),
            ['idnormas_im' => (int) $datos['idnormas_im']]
        );
    }

    /** Encola deteccion o calculo de columnas para 04_02 y 05_B_01. */
    public function encolarColumnas(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'idnormas_im' => 'required|integer|exists:Normas_IM,idnormas_im',
            'Analisis_PDF' => 'required|file|mimes:pdf|max:10240',
            'XRF_Columnas' => 'nullable|array|min:1|max:3',
            'XRF_Columnas.*' => 'required_with:XRF_Columnas|integer|distinct|between:1,20',
        ]);

        return $this->crearTrabajo(
            $request,
            'xrf_columnas',
            [$request->file('Analisis_PDF')],
            [
                'idnormas_im' => (int) $datos['idnormas_im'],
                'columnas' => array_values($datos['XRF_Columnas'] ?? []),
            ]
        );
    }

    /** Copia entradas a almacenamiento privado y crea el registro antes de despachar. */
    private function crearTrabajo(Request $request, string $tipo, array $archivos, array $contexto): JsonResponse
    {
        $usuarioId = (int) $request->user()->getAuthIdentifier();
        $trabajoId = (string) Str::uuid();
        $entradas = [];

        try {
            foreach ($archivos as $indice => $archivo) {
                if (!$archivo instanceof UploadedFile) {
                    throw new \RuntimeException('No se recibio un PDF valido.');
                }
                $ruta = "procesamientos/{$usuarioId}/{$trabajoId}/entrada-{$indice}.pdf";
                Storage::disk('local')->putFileAs(dirname($ruta), $archivo, basename($ruta));
                $entradas[] = [
                    'ruta' => $ruta,
                    'nombre' => $archivo->getClientOriginalName(),
                    'mime' => $archivo->getMimeType(),
                ];
            }

            $trabajo = TrabajoProcesamiento::create([
                'id' => $trabajoId,
                'usuario_id' => $usuarioId,
                'tipo' => $tipo,
                'estado' => TrabajoProcesamiento::ESTADO_PENDIENTE,
                'mensaje' => 'Procesando PDF XRF...',
                'contexto' => array_merge($contexto, ['entradas' => $entradas]),
                'expira_at' => now()->addDay(),
            ]);

            ProcesarXrfJob::dispatch($trabajo->id);

            return response()->json([
                'ok' => true,
                'trabajo' => [
                    'id' => $trabajo->id,
                    'estado_url' => route('procesamientos.estado', $trabajo->id),
                ],
            ], 202);
        } catch (Throwable $error) {
            // Evita residuos cuando falle el almacenamiento o la insercion de la cola.
            Storage::disk('local')->deleteDirectory("procesamientos/{$usuarioId}/{$trabajoId}");
            TrabajoProcesamiento::find($trabajoId)?->marcarError($error, 'No fue posible iniciar el procesamiento XRF.');

            return response()->json([
                'ok' => false,
                'message' => 'No fue posible iniciar el procesamiento XRF.',
            ], 422);
        }
    }
}
