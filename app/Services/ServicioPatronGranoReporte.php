<?php

namespace App\Services;

use App\Models\Normas_IM\PatronGranoIM;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ServicioPatronGranoReporte
{
    /** Entrega al navegador solo identificador, nombre y URL pública para la vista previa. */
    public function catalogoParaVista(): Collection
    {
        return PatronGranoIM::query()->orderBy('valor_grano')->get()->map(function ($patron) {
            return [
                'id' => $patron->id,
                'nombre' => $patron->nombre,
                'valor_grano' => (string) $patron->valor_grano,
                // asset() respeta el dominio, HTTPS y el subdirectorio reales de la solicitud actual.
                'url_imagen' => asset('storage/' . ltrim($patron->ruta_imagen, '/')),
            ];
        })->values();
    }

    /**
     * Copia la imagen maestra al expediente y congela sus datos visibles.
     * Edit conserva la copia anterior salvo que el técnico vuelva a seleccionar el catálogo.
     */
    public function construirHistorico(
        Request $request,
        string $formato,
        string $contrato,
        string $numeroReporte,
        ?array $historico = null
    ): ?array {
        // El check de la tarjeta elegida controla la existencia completa del tamaño de grano en el reporte.
        if (!$request->boolean('Patron_Grano.activo')) {
            return null;
        }

        $idPatron = (int) $request->input('Patron_Grano.id', 0);
        if ($idPatron <= 0) {
            return null;
        }

        $descripcion = trim((string) $request->input('Patron_Grano.descripcion', ''));
        $layout = $this->normalizarLayout($request->input('Patron_Grano.layout'));
        $usarVersionCatalogo = $request->boolean('Patron_Grano.usar_version_catalogo');
        $mismoPatron = is_array($historico)
            && (int) ($historico['id'] ?? 0) === $idPatron
            && !$usarVersionCatalogo;
        $rutaHistorica = $mismoPatron ? (string) ($historico['ruta_imagen'] ?? '') : '';
        $relativaHistorica = $this->rutaRelativa($rutaHistorica);

        if ($rutaHistorica !== '' && Storage::disk('public')->exists($relativaHistorica)) {
            return array_merge($historico, [
                'descripcion' => $descripcion,
                'layout' => $layout,
            ]);
        }

        $patron = PatronGranoIM::find($idPatron);
        if (!$patron || !Storage::disk('public')->exists($patron->ruta_imagen)) {
            throw ValidationException::withMessages([
                'Patron_Grano.id' => 'El patrón seleccionado ya no está disponible.',
            ]);
        }

        $formatoSeguro = strtoupper(str_replace(['/', '\\'], '_', $formato));
        $formatoSeguro = preg_replace('/[^A-Z0-9_-]/', '', $formatoSeguro) ?: 'FORMATO_IM';
        $contratoSeguro = Str::slug($contrato) ?: 'sin-contrato';
        $reporteSeguro = Str::slug($numeroReporte) ?: 'sin-reporte';
        $extension = strtolower(pathinfo($patron->ruta_imagen, PATHINFO_EXTENSION) ?: 'png');
        $directorio = "Reportes/{$formatoSeguro}/{$contratoSeguro}/{$reporteSeguro}/PATRON_GRANO";
        $rutaCopia = $directorio . '/patron-' . $patron->id . '-' . Str::uuid() . '.' . $extension;

        if (!Storage::disk('public')->copy($patron->ruta_imagen, $rutaCopia)) {
            throw ValidationException::withMessages([
                'Patron_Grano.id' => 'No fue posible conservar la imagen seleccionada dentro del reporte.',
            ]);
        }

        return [
            'version' => 1,
            'id' => $patron->id,
            'nombre' => $patron->nombre,
            'valor_grano' => (string) $patron->valor_grano,
            'descripcion' => $descripcion,
            'ruta_imagen' => 'storage/' . $rutaCopia,
            'layout' => $layout,
        ];
    }

    /** Convierte la copia histórica en una fotografía posicionable para cualquier anexo compatible. */
    public function agregarAlPdf(
        array &$fotos,
        array $detallesGenerales,
        int $pagina = 1,
        string $posicion = 'abajo_izquierda'
    ): void {
        $patron = $detallesGenerales['PATRON_GRANO'] ?? null;
        if (!is_array($patron) || empty($patron['ruta_imagen'])) {
            return;
        }

        $rutaFisica = storage_path('app/public/' . $this->rutaRelativa((string) $patron['ruta_imagen']));
        // Evita enviar carpetas o rutas historicas incompletas al motor de PDF como si fueran imagenes.
        if (!File::isFile($rutaFisica)) {
            return;
        }

        // Reportes anteriores no tienen layout y conservan la posición histórica inferior izquierda.
        $layout = $this->normalizarLayout($patron['layout'] ?? [
            'pagina' => $pagina,
            'posicion' => $posicion,
        ]);

        $fotos[] = [
            'path' => $rutaFisica,
            'comment' => trim((string) ($patron['descripcion'] ?? '')) !== ''
                ? (string) $patron['descripcion']
                : (string) ($patron['nombre'] ?? 'PATRÓN COMPARATIVO DE GRANO'),
            'es_cuadro_texto' => 0,
            'una_hoja' => $layout['posicion'] === 'pagina_completa' ? 1 : 0,
            'pagina' => $layout['pagina'],
            'posicion' => $layout['posicion'],
            'origen_automatico' => 'patron_grano_historico',
        ];
    }

    /** Retira una copia sustituida solo cuando el reporte ya apunta a otra ruta. */
    public function eliminarCopiaSustituida(string $rutaAnterior, string $rutaNueva): void
    {
        if ($rutaAnterior !== '' && $rutaAnterior !== $rutaNueva) {
            Storage::disk('public')->delete($this->rutaRelativa($rutaAnterior));
        }
    }

    private function rutaRelativa(string $rutaPublica): string
    {
        return ltrim(str_replace('storage/', '', $rutaPublica), '/');
    }

    /** Normaliza la celda seleccionada para que el tamaño de grano nunca guarde coordenadas inválidas. */
    private function normalizarLayout($layout): array
    {
        $layout = is_array($layout) ? $layout : [];
        $permitidas = [
            'arriba_izquierda',
            'arriba_derecha',
            'abajo_izquierda',
            'abajo_derecha',
            'pagina_completa',
        ];
        $posicion = (string) ($layout['posicion'] ?? 'abajo_izquierda');

        return [
            'pagina' => max(1, (int) ($layout['pagina'] ?? 1)),
            'posicion' => in_array($posicion, $permitidas, true) ? $posicion : 'abajo_izquierda',
        ];
    }
}
