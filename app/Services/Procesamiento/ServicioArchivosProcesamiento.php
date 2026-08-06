<?php

namespace App\Services\Procesamiento;

use App\Models\Procesamiento\TrabajoProcesamiento;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ServicioArchivosProcesamiento
{
    /**
     * Restaura los PDF privados si el navegador se recargo y perdio el FileList.
     * El UUID, usuario y estado se verifican antes de inyectar cualquier archivo.
     */
    public function restaurarXrf(Request $request): void
    {
        if ($request->hasFile('Analisis_PDF') || !$request->filled('XRF_Trabajo_ID')) {
            return;
        }

        $trabajo = TrabajoProcesamiento::query()
            ->whereKey((string) $request->input('XRF_Trabajo_ID'))
            ->where('usuario_id', $request->user()->getAuthIdentifier())
            ->whereIn('tipo', ['xrf_multiple', 'xrf_columnas'])
            ->where('estado', TrabajoProcesamiento::ESTADO_COMPLETADO)
            ->first();

        if (!$trabajo) {
            return;
        }

        $archivos = array_map(static function (array $entrada): UploadedFile {
            $ruta = (string) ($entrada['ruta'] ?? '');
            abort_unless($ruta !== '' && Storage::disk('local')->exists($ruta), 422, 'El PDF temporal ya no esta disponible.');

            return new UploadedFile(
                Storage::disk('local')->path($ruta),
                (string) ($entrada['nombre'] ?? 'analisis.pdf'),
                $entrada['mime'] ?? 'application/pdf',
                null,
                true
            );
        }, $trabajo->contexto['entradas'] ?? []);

        // Los formatos de columnas reciben uno; los de disparos reciben un arreglo.
        $request->files->set('Analisis_PDF', $trabajo->tipo === 'xrf_columnas'
            ? ($archivos[0] ?? null)
            : $archivos);
    }
}
