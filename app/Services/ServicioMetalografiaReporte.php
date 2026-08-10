<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

/**
 * Centraliza las reglas compartidas del análisis metalográfico usado por los
 * formatos IM. El navegador solamente captura puntos; este servicio vuelve a
 * calcular los resultados que se conservan en el reporte y prepara el PDF.
 */
class ServicioMetalografiaReporte
{
    /** Normaliza las posiciones editables de Imagen 1 e Imagen 2 antes de conservarlas. */
    public function normalizarLayoutAnalisis($layout): array
    {
        $layout = is_array($layout) ? $layout : [];

        return [
            'imagen' => $this->normalizarCeldaReporte(
                is_array($layout['imagen'] ?? null) ? $layout['imagen'] : [],
                'arriba_izquierda'
            ),
            'resultados' => $this->normalizarCeldaReporte(
                is_array($layout['resultados'] ?? null) ? $layout['resultados'] : [],
                'arriba_derecha'
            ),
        ];
    }

    /** Acepta únicamente páginas positivas y posiciones compatibles con el anexo fotográfico. */
    private function normalizarCeldaReporte(array $celda, string $posicionPredeterminada): array
    {
        $permitidas = [
            'arriba_izquierda',
            'arriba_derecha',
            'abajo_izquierda',
            'abajo_derecha',
            'pagina_completa',
        ];
        $posicion = (string) ($celda['posicion'] ?? $posicionPredeterminada);

        return [
            'pagina' => max(1, (int) ($celda['pagina'] ?? 1)),
            'posicion' => in_array($posicion, $permitidas, true)
                ? $posicion
                : $posicionPredeterminada,
        ];
    }

    /**
     * Normaliza las líneas y calcula cruces, granos completos, suma y promedio.
     * Cada grano completo vale 1 y cada extremo de la línea vale 0.5.
     */
    public function normalizarConteoGranos(?string $json): ?array
    {
        if ($json === null || trim($json) === '') {
            return null;
        }

        $datos = json_decode($json, true);
        if (!is_array($datos) || !isset($datos['lineas']) || !is_array($datos['lineas'])) {
            return null;
        }

        $lineas = [];
        foreach (array_slice($datos['lineas'], 0, 50) as $indice => $linea) {
            if (!is_array($linea)) {
                continue;
            }

            $coordenadas = [];
            foreach (['x1', 'y1', 'x2', 'y2'] as $campo) {
                if (!isset($linea[$campo]) || !is_numeric($linea[$campo])) {
                    continue 2;
                }
                $coordenadas[$campo] = max(0, min(1, (float) $linea[$campo]));
            }

            if (hypot($coordenadas['x2'] - $coordenadas['x1'], $coordenadas['y2'] - $coordenadas['y1']) < 0.001) {
                continue;
            }

            $marcadores = array_values(array_unique(array_map(
                static fn ($valor) => round((float) $valor, 6),
                array_filter(
                    array_slice(is_array($linea['marcadores'] ?? null) ? $linea['marcadores'] : [], 0, 500),
                    static fn ($valor) => is_numeric($valor) && (float) $valor > 0.02 && (float) $valor < 0.98
                )
            )));
            sort($marcadores, SORT_NUMERIC);

            $cruces = count($marcadores);
            $completos = max(0, $cruces - 1);
            $lineas[] = array_merge([
                'id' => (int) ($linea['id'] ?? ($indice + 1)),
            ], $coordenadas, [
                'marcadores' => $marcadores,
                'cruces' => $cruces,
                'granos_completos' => $completos,
                'extremos_parciales' => 1.0,
                'conteo' => $completos + 1.0,
            ]);
        }

        $suma = array_sum(array_column($lineas, 'conteo'));

        return [
            'version' => 1,
            'regla' => 'extremos_0.5_completos_1',
            'lineas' => $lineas,
            'resumen' => [
                'numero_lineas' => count($lineas),
                'suma' => round($suma, 3),
                'promedio' => count($lineas) > 0 ? round($suma / count($lineas), 3) : 0.0,
            ],
        ];
    }

    /** Construye el texto automático para reportes históricos sin redacción revisada. */
    public function construirTextoResultados(
        array $analisis,
        array $conteo,
        array $datosEquipo = [],
        array $patronGrano = []
    ): string
    {
        $lineas = [
            'RESULTADOS DEL ANÁLISIS METALOGRÁFICO',
            '',
            'Fases presentes: ' . (trim((string) ($datosEquipo['FASES_PRESENTES'] ?? '')) ?: '---'),
            'Morfología de la microestructura: ---',
            '% fracción volumétrica Perlita / zonas oscuras: ' . number_format((float) ($analisis['porcentaje_perlita'] ?? 0), 3) . ' %',
            '% fracción volumétrica Ferrita / zonas claras: ' . number_format((float) ($analisis['porcentaje_ferrita'] ?? 0), 3) . ' %',
            'Método de tamaño de grano ASTM E112: Comparativo',
            'Tamaño de grano: ' . (trim((string) ($patronGrano['valor_grano'] ?? $patronGrano['nombre'] ?? '')) ?: '---'),
            'Bandeamiento: ---',
            'Magnificación: 100 X',
            'Analizador: Fiji',
        ];

        $lineasConteo = is_array($conteo['lineas'] ?? null) ? $conteo['lineas'] : [];
        if ($lineasConteo === []) {
            $lineas[] = '';
            $lineas[] = 'Conteo lineal de granos: sin líneas registradas.';
            return implode("\n", $lineas);
        }

        $lineas[] = '';
        $lineas[] = 'CONTEO LINEAL DE GRANOS';
        $lineas[] = 'Regla: cada grano completo = 1; cada extremo = 0.5.';
        foreach ($lineasConteo as $indice => $linea) {
            $lineas[] = sprintf(
                'L%d: cruces %d; completos %d; extremos 0.5 + 0.5; conteo %.1f.',
                (int) ($linea['id'] ?? ($indice + 1)),
                (int) ($linea['cruces'] ?? 0),
                (int) ($linea['granos_completos'] ?? 0),
                (float) ($linea['conteo'] ?? 0)
            );
        }

        $resumen = is_array($conteo['resumen'] ?? null) ? $conteo['resumen'] : [];
        $lineas[] = sprintf(
            'Resumen: %d líneas; suma %.1f; promedio %.3f granos por línea.',
            (int) ($resumen['numero_lineas'] ?? count($lineasConteo)),
            (float) ($resumen['suma'] ?? 0),
            (float) ($resumen['promedio'] ?? 0)
        );

        return implode("\n", $lineas);
    }

    /**
     * Inserta la micrografía y sus resultados en los dos espacios superiores
     * del anexo. No duplica registros dentro de Fotos_Reporte.
     */
    public function agregarAnalisisAlPdf(array &$fotos, array $detallesGenerales, array $datosEquipo = []): void
    {
        $analisis = $detallesGenerales['ANALISIS_IMAGEN'] ?? null;
        if (!is_array($analisis) || empty($analisis['usar_en_reporte'])) {
            return;
        }
        $layout = $this->normalizarLayoutAnalisis($analisis['layout_reporte'] ?? []);

        // La copia PNG evita fallos de navegador/DomPDF con originales TIFF; reportes anteriores usan original.
        $rutaPublica = (string) (
            $analisis['rutas']['imagen_visual']
            ?? $analisis['rutas']['original']
            ?? ''
        );
        $rutaFisica = $rutaPublica !== ''
            ? storage_path('app/public/' . ltrim(str_replace('storage/', '', $rutaPublica), '/'))
            : '';

        if ($rutaFisica !== '' && File::exists($rutaFisica)) {
            $comentarioImagen = trim((string) ($analisis['comentario_imagen_reporte'] ?? ''));
            $fotos[] = [
                'path' => $rutaFisica,
                // Los reportes anteriores conservan el texto tradicional como valor de respaldo.
                'comment' => $comentarioImagen !== '' ? $comentarioImagen : 'FOTOMICROGRAFÍA ANALIZADA',
                'es_cuadro_texto' => 0,
                'pagina' => $layout['imagen']['pagina'],
                'posicion' => $layout['imagen']['posicion'],
                'una_hoja' => $layout['imagen']['posicion'] === 'pagina_completa' ? 1 : 0,
                'origen_automatico' => 'analisis_imagen_original',
            ];
        }

        $conteo = is_array($detallesGenerales['CONTEO_GRANOS'] ?? null)
            ? $detallesGenerales['CONTEO_GRANOS']
            : [];
        $descripcion = trim((string) ($analisis['descripcion_reporte'] ?? ''));
        $fotos[] = [
            'path' => null,
            'comment' => $descripcion !== ''
                ? $descripcion
                : $this->construirTextoResultados(
                    $analisis,
                    $conteo,
                    $datosEquipo,
                    is_array($detallesGenerales['PATRON_GRANO'] ?? null)
                        ? $detallesGenerales['PATRON_GRANO']
                        : []
                ),
            'es_cuadro_texto' => 1,
            'pagina' => $layout['resultados']['pagina'],
            'posicion' => $layout['resultados']['posicion'],
            'una_hoja' => $layout['resultados']['posicion'] === 'pagina_completa' ? 1 : 0,
            'origen_automatico' => 'resultados_analisis_imagen',
        ];
    }
}
