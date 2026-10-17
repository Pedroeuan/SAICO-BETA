<?php

namespace App\Services\Procesamiento;

use App\Models\Procesamiento\TrabajoProcesamiento;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ServicioArchivosProcesamiento
{
    /** Reutiliza la captura del servidor únicamente para el mismo PDF y disparos. */
    public function capturaColumnasXrf(Request $request, UploadedFile $archivo, array $columnas): ?array
    {
        if (!$request->filled('XRF_Trabajo_ID') || !$request->user()) return null;
        $trabajo = TrabajoProcesamiento::query()
            ->whereKey((string) $request->input('XRF_Trabajo_ID'))
            ->where('usuario_id', $request->user()->getAuthIdentifier())
            ->where('tipo', 'xrf_columnas')
            ->where('estado', TrabajoProcesamiento::ESTADO_COMPLETADO)
            ->first();
        return $trabajo ? $this->capturaVerificada($trabajo, $archivo, $columnas) : null;
    }

    public function capturaVerificada(TrabajoProcesamiento $trabajo, UploadedFile $archivo, array $columnas): ?array
    {
        if ($trabajo->tipo !== 'xrf_columnas' || $trabajo->estado !== TrabajoProcesamiento::ESTADO_COMPLETADO
            || ($trabajo->expira_at && $trabajo->expira_at->isPast())) return null;
        $resultado = $trabajo->resultadoArray() ?? [];
        if (array_map('intval', $resultado['columnas_seleccionadas'] ?? []) !== array_values($columnas)) return null;
        $ruta = (string) ($trabajo->contexto['entradas'][0]['ruta'] ?? '');
        if ($ruta === '' || !Storage::disk('local')->exists($ruta)) return null;
        if (hash_file('sha256', Storage::disk('local')->path($ruta)) !== hash_file('sha256', $archivo->getRealPath())) return null;
        $captura = $resultado['captura'] ?? null;
        return is_array($captura) && str_starts_with((string) ($captura['data_url'] ?? ''), 'data:image/png;base64,')
            && array_map('intval', $captura['columnas'] ?? []) === array_values($columnas) ? $captura : null;
    }

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
