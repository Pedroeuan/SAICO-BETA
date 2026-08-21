<?php

namespace App\Http\Controllers\Procesamiento;

use App\Http\Controllers\Controller;
use App\Jobs\Procesamiento\GenerarReportePdfJob;
use App\Models\Procesamiento\TrabajoProcesamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProcesamientoPdfController extends Controller
{
    /** Crea el trabajo y muestra una pagina ligera mientras se genera el reporte. */
    public function pagina(Request $request, int $reporte, string $formato)
    {
        abort_unless(array_key_exists($formato, GenerarReportePdfJob::FORMATOS), 404);

        // FOR-PIMP-04/03 permite seleccionar sus plantillas en espanol o ingles.
        // Los demas formatos conservan exactamente su comportamiento anterior.
        $idioma = strtolower((string) $request->query('idioma', 'es'));
        $idioma = $formato === '04_03' && in_array($idioma, ['es', 'en'], true)
            ? $idioma
            : 'es';

        $trabajo = TrabajoProcesamiento::create([
            'id' => (string) Str::uuid(),
            'usuario_id' => (int) $request->user()->getAuthIdentifier(),
            'tipo' => 'reporte_pdf',
            'estado' => TrabajoProcesamiento::ESTADO_PENDIENTE,
            'mensaje' => 'Generando reporte PDF...',
            'contexto' => [
                'reporte_id' => $reporte,
                'formato' => $formato,
                'idioma' => $idioma,
            ],
            'expira_at' => now()->addHours(8),
        ]);

        GenerarReportePdfJob::dispatch($trabajo->id);

        return view('Procesamiento.reporte-pdf', [
            'trabajo' => $trabajo,
            'estadoUrl' => route('procesamientos.estado', $trabajo->id),
        ]);
    }
}
