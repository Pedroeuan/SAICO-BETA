<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/** Conserva las hojas ya renderizadas; nunca modifica las imágenes originales. */
class PdfSecciones0402
{
    /**
     * Devuelve el PDF de una sección vigente o ejecuta $generar si cambió.
     * El HTML incluye los textos y estilos; la huella incluye las imágenes.
     * No ejecuta Fiji/XRF ni comprime, recorta o sustituye los originales.
     */
    public function obtener(int $reporte, string $seccion, string $html, callable $generar): string
    {
        if (!in_array($seccion, ['datos', 'fotos'], true)) throw new RuntimeException('Sección PDF no permitida.');
        $imagenes = [];
        // Una imagen puede reemplazarse conservando nombre, tamaño y fecha.
        // Comparar su contenido evita reutilizar una hoja con la imagen anterior.
        preg_match_all('/<img\b[^>]*\bsrc\s*=\s*["\']([^"\']*)["\']/i', $html, $coincidencias);
        foreach (array_unique($coincidencias[1]) as $src) {
            $ruta = html_entity_decode($src, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            if (is_file($ruta)) $imagenes[$ruta] = hash_file('sha256', $ruta);
        }
        // Cada reporte mantiene una caché independiente para datos y fotografías.
        // Cambiar un texto invalida solo las secciones cuyo HTML realmente cambió.
        $firma = hash('sha256', serialize([1, $html, $imagenes, config('dompdf.options'), 'letter', 'portrait']));
        $directorio = "reportes_generados/04_02/{$reporte}/secciones";
        $ruta = "{$directorio}/{$seccion}-{$firma}.pdf";
        $disco = Storage::disk('local');
        if ($disco->exists($ruta)) {
            // Evita volver a renderizar con Dompdf una sección sin cambios.
            $contenido = $disco->get($ruta);
            if (is_string($contenido) && str_starts_with($contenido, '%PDF')) return $contenido;
        }

        // Genera y valida primero: un fallo deja disponible la copia anterior.
        $contenido = $generar();
        if (!is_string($contenido) || !str_starts_with($contenido, '%PDF')) throw new RuntimeException('La sección generada no contiene un PDF válido.');
        // La caché es auxiliar: si el disco no permite guardarla, entrega el PDF recién generado.
        $temporal = $ruta.'.'.Str::uuid().'.tmp';
        try {
            if ($disco->put($temporal, $contenido) && $disco->move($temporal, $ruta)) {
                // Retira únicamente PDFs auxiliares obsoletos de esta sección.
                // No elimina fotos, recortes XRF, micrografías ni datos del reporte.
                foreach ($disco->files($directorio) as $anterior) {
                    if ($anterior !== $ruta && str_starts_with(basename($anterior), $seccion.'-') && str_ends_with($anterior, '.pdf')) $disco->delete($anterior);
                }
            }
        } catch (\Throwable $error) {
            report($error);
        } finally {
            if ($disco->exists($temporal)) $disco->delete($temporal);
        }
        return $contenido;
    }
}
