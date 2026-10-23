<?php

namespace App\Http\Controllers\Procesamiento;

use App\Http\Controllers\Controller;
use App\Jobs\Procesamiento\GenerarReportePdfJob;
use App\Services\ServicioPdfGenerado;
use Illuminate\Http\Request;

class ProcesamientoPdfController extends Controller
{
    /** Crea el trabajo y muestra una pagina ligera mientras se genera el reporte. */
    public function pagina(
        Request $request,
        int $reporte,
        string $formato,
        ServicioPdfGenerado $pdfGenerado
    )
    {
        abort_unless(array_key_exists($formato, GenerarReportePdfJob::FORMATOS), 404);
        $descargar = $request->boolean('download');
        // Impide mostrar un PDF antiguo del navegador. La copia vigente del
        // servidor sí se reutiliza; estas cabeceras no fuerzan otra generación.
        $cabeceras = in_array($formato, ['04_02', '05_B_01'], true) ? [
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ] : [];

        // FOR-PIMP-04/03 permite seleccionar sus plantillas en espanol o ingles.
        // Los demas formatos conservan exactamente su comportamiento anterior.
        $idioma = strtolower((string) $request->query('idioma', 'es'));
        $idioma = $formato === '04_03' && in_array($idioma, ['es', 'en'], true)
            ? $idioma
            : 'es';

        // Un reporte sin cambios abre el archivo privado ya generado. No se crea
        // otro trabajo ni se vuelve a ejecutar Dompdf/FPDI.
        if ($ruta = $pdfGenerado->rutaVigente($reporte, $formato, $idioma)) {
            $respuesta = response()->file($ruta, array_merge($cabeceras, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => ($descargar ? 'attachment' : 'inline') . '; filename="reporte.pdf"',
            ]));
            return in_array($formato, ['04_02', '05_B_01'], true) ? $respuesta->setPrivate() : $respuesta;
        }

        $trabajo = $pdfGenerado->programar(
            $reporte,
            $formato,
            $idioma,
            (int) $request->user()->getAuthIdentifier()
        );

        // Puede aparecer entre la primera comprobacion y la programacion si
        // otro worker termino la misma version.
        if (!$trabajo && ($ruta = $pdfGenerado->rutaVigente($reporte, $formato, $idioma))) {
            $respuesta = response()->file($ruta, array_merge($cabeceras, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="reporte.pdf"',
            ]));
            return in_array($formato, ['04_02', '05_B_01'], true) ? $respuesta->setPrivate() : $respuesta;
        }

        abort_unless($trabajo, 409);

        return response()->view('Procesamiento.reporte-pdf', [
            'trabajo' => $trabajo,
            'estadoUrl' => route('procesamientos.estado', $trabajo->id),
            'descargar' => $descargar,
        ], 200, $cabeceras);
    }
}
