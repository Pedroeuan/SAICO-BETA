<?php

namespace App\Http\Controllers\Reporte\IM;

use App\Http\Controllers\Controller;

use App\Models\OC\OC;
use App\Models\Prueba\prueba;
use App\Models\Formato\formato;
use App\Models\Reporte\reporte;
use App\Models\Clientes\clientes;
use App\Models\detallesOC\detallesOC;
use App\Models\Manifiesto\manifiesto;
use App\Models\Reporte\Firma_Reporte;
use App\Models\Reporte\Fotos_Reporte;
use App\Models\Solicitudes\Solicitudes;
use App\Models\Lineal_Ideal\Lineal_Ideal;
use App\Models\Norma_Codigo\norma_codigo;
use App\Models\OrdenServicio\Firmantes_OS;
use App\Models\PruebaAplica\Prueba_Aplica;
use App\Models\OrdenServicio\Orden_Servicio;
use App\Models\EquiposyConsumibles\devolucion;
use App\Models\Solicitudes\detalles_solicitud;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\Reporte\Grupo_Juntas_Detalles_Re;
use App\Models\OrdenServicio\Orden_Servicio_Prueba;
use App\Models\OrdenServicio\Grupo_Juntas_Detalles_OS;
use App\Models\Normas_IM\Normas_IM;
use App\Services\ServicioAnalisisPdfXrf;
use App\Services\ServicioImagenesPdfXrf;
use App\Services\ServicioAnalisisColumnasPdfXrf;
use App\Services\ServicioCapturaColumnasPdfXrf;
use App\Services\ServicioRegistrosFotos;
use App\Services\ServicioAnalisisImagenImageJ;
use App\Services\ServicioPatronGranoReporte;
use App\Services\ServicioMetalografiaReporte;
use Illuminate\Http\UploadedFile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/*PDF */
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Barryvdh\DomPDF\Facade\Pdf;

class FOR_PIMP_04_02Controller extends Controller
{
    /**
     * Normaliza el conteo lineal enviado por el lienzo del navegador.
     *
     * Regla acordada con el técnico:
     * - Cada grano completo entre cruces vale 1.
     * - El grano de cada extremo vale 0.5 (1.0 entre ambos extremos).
     * - El servidor vuelve a calcular conteos, suma y promedio; no confía en totales del cliente.
     */
    private function normalizarConteoGranos(?string $json): ?array
    {
        if ($json === null || trim($json) === '') {
            return null;
        }

        $datos = json_decode($json, true);
        if (!is_array($datos) || !isset($datos['lineas']) || !is_array($datos['lineas'])) {
            return null;
        }

        // Se limita el volumen de información para evitar cargas desproporcionadas en el JSON del reporte.
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

            // Cada marcador es una posición normalizada (0-1) sobre la trayectoria de la línea.
            $marcadores = array_values(array_unique(array_map(
                static fn ($valor) => round((float) $valor, 6),
                array_filter(
                    array_slice(is_array($linea['marcadores'] ?? null) ? $linea['marcadores'] : [], 0, 500),
                    static fn ($valor) => is_numeric($valor) && (float) $valor > 0.02 && (float) $valor < 0.98
                )
            )));
            sort($marcadores, SORT_NUMERIC);
            // Con N cruces existen N-1 granos completos entre límites, más los dos medios extremos.
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

        // El resumen se deriva nuevamente de las líneas normalizadas.
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

    /**
     * Construye el texto compacto que ocupará el segundo espacio del anexo fotográfico.
     *
     * Se usa como respaldo para reportes antiguos que todavía no contienen una descripción
     * revisada desde Create/Edit; los reportes nuevos conservan el texto aprobado por el técnico.
     */
    private function construirTextoResultadosMetalograficos(array $analisis, array $conteo): string
    {
        $fase = ($analisis['fase_seleccionada'] ?? 'perlita') === 'ferrita'
            ? 'Ferrita / fase clara'
            : 'Perlita / fase oscura';

        $lineas = [
            'RESULTADOS DEL ANÁLISIS METALOGRÁFICO',
            'Archivo: ' . ($analisis['archivo_original'] ?? 'Sin nombre'),
            'Conversión: 8 bits',
            'Umbral: ' . ($analisis['umbral_minimo'] ?? 0) . '–' . ($analisis['umbral_maximo'] ?? 0),
            'Fase revisada: ' . $fase,
            'Perlita / fase oscura: ' . number_format((float) ($analisis['porcentaje_perlita'] ?? 0), 3) . ' %',
            'Ferrita / fase clara: ' . number_format((float) ($analisis['porcentaje_ferrita'] ?? 0), 3) . ' %',
            'Total verificado: 100.000 %',
            'Método: ' . ($analisis['metodo_medicion'] ?? 'ImageJ Area Fraction / Measure'),
        ];

        $lineasConteo = is_array($conteo['lineas'] ?? null) ? $conteo['lineas'] : [];
        if ($lineasConteo !== []) {
            $lineas[] = '';
            $lineas[] = 'CONTEO LINEAL DE GRANOS';
            $lineas[] = 'Regla: cada grano completo = 1; cada extremo = 0.5.';

            // Cada renglón conserva los datos auditables que el técnico confirmó en el contador.
            foreach ($lineasConteo as $indice => $linea) {
                $numero = (int) ($linea['id'] ?? ($indice + 1));
                $lineas[] = sprintf(
                    'L%d: cruces %d; completos %d; extremos 0.5 + 0.5; conteo %.1f.',
                    $numero,
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
        } else {
            $lineas[] = '';
            $lineas[] = 'Conteo lineal de granos: sin líneas registradas.';
        }

        return implode("\n", $lineas);
    }

    /**
     * Agrega al PDF la micrografía y el cuadro de resultados sin duplicarlos en Fotos_Reporte.
     * Los espacios inferiores quedan libres para la futura imagen maestra y la foto de la pieza.
     */
    private function agregarAnalisisSeleccionadoAlPdf(array &$fotos, array $detallesGenerales): void
    {
        $analisis = $detallesGenerales['ANALISIS_IMAGEN'] ?? null;
        if (!is_array($analisis) || empty($analisis['usar_en_reporte'])) {
            return;
        }
        $layout = app(ServicioMetalografiaReporte::class)->normalizarLayoutAnalisis(
            $analisis['layout_reporte'] ?? []
        );

        $rutaPublica = (string) ($analisis['rutas']['original'] ?? '');
        $rutaFisica = $rutaPublica !== ''
            ? storage_path('app/public/' . ltrim(str_replace('storage/', '', $rutaPublica), '/'))
            : '';

        // Espacio 1: se reutiliza el archivo original conservado por Fiji; no se crea otra copia.
        if ($rutaFisica !== '' && File::exists($rutaFisica)) {
            $fotos[] = [
                'path' => $rutaFisica,
                'comment' => trim((string) ($analisis['comentario_imagen_reporte'] ?? '')) !== ''
                    ? (string) $analisis['comentario_imagen_reporte']
                    : 'FOTOMICROGRAFÍA ANALIZADA',
                'es_cuadro_texto' => 0,
                'una_hoja' => $layout['imagen']['posicion'] === 'pagina_completa' ? 1 : 0,
                'pagina' => $layout['imagen']['pagina'],
                'posicion' => $layout['imagen']['posicion'],
                'origen_automatico' => 'analisis_imagen_original',
            ];
        }

        // Espacio 2: muestra la redacción revisada; solo reportes antiguos recurren al generador.
        $fotos[] = [
            'path' => null,
            // El texto revisado en Create/Edit tiene prioridad; el generador queda como respaldo histórico.
            'comment' => trim((string) ($analisis['descripcion_reporte'] ?? '')) !== ''
                ? (string) $analisis['descripcion_reporte']
                : $this->construirTextoResultadosMetalograficos(
                    $analisis,
                    is_array($detallesGenerales['CONTEO_GRANOS'] ?? null)
                        ? $detallesGenerales['CONTEO_GRANOS']
                        : []
                ),
            'es_cuadro_texto' => 1,
            'una_hoja' => $layout['resultados']['posicion'] === 'pagina_completa' ? 1 : 0,
            'pagina' => $layout['resultados']['pagina'],
            'posicion' => $layout['resultados']['posicion'],
            'origen_automatico' => 'resultados_analisis_imagen',
        ];
    }

    /** Normaliza la página y el cuadrante de una foto para que el PDF reciba una distribución válida. */
    private function normalizeFotoLayout($pagina, $posicion, $index): array
    {
        $posicionesPermitidas = [
            'arriba_izquierda',
            'arriba_derecha',
            'abajo_izquierda',
            'abajo_derecha',
            'pagina_completa',
        ];
        $posicionesPredeterminadas = array_slice($posicionesPermitidas, 0, 4);
        $indice = max(0, (int) $index);
        $paginaNormalizada = max(1, (int) ($pagina ?: (intdiv($indice, 4) + 1)));
        $posicionNormalizada = in_array($posicion, $posicionesPermitidas, true)
            ? $posicion
            : $posicionesPredeterminadas[$indice % 4];

        return [
            'pagina' => $paginaNormalizada,
            'posicion' => $posicionNormalizada,
            'una_hoja' => $posicionNormalizada === 'pagina_completa' ? 1 : 0,
        ];
    }

    /** Conserva únicamente disparos existentes y permite trabajar con uno, dos o tres. */
    private function normalizarColumnasXrf(array $analysis, array $requestedColumns): array
    {
        $available = array_values(array_unique(array_map('intval', $analysis['columnas'] ?? [])));
        $requested = array_values(array_unique(array_map('intval', $requestedColumns)));
        $selected = array_values(array_intersect($requested, $available));

        if ($selected === []) {
            $selected = array_slice($available, 0, 3);
        }

        return array_slice($selected, 0, 3);
    }

    /** Procesa un solo PDF y calcula las tres columnas elegidas por el usuario. */
    public function extraerAnalisisPdf(
        Request $request,
        ServicioAnalisisColumnasPdfXrf $service,
        ServicioCapturaColumnasPdfXrf $captureService
    ) {
        $validated = $request->validate([
            'idnormas_im' => 'required|integer|exists:Normas_IM,idnormas_im',
            'Analisis_PDF' => 'required|file|mimes:pdf|max:10240',
            'XRF_Columnas' => 'nullable|array|min:1|max:3',
            'XRF_Columnas.*' => 'required_with:XRF_Columnas|integer|distinct|between:1,20',
        ]);
        $file = $request->file('Analisis_PDF');
        try {
            $analysis = $service->parseUploadedFile($file);
            if (empty($validated['XRF_Columnas'])) {
                return response()->json([
                    'archivo' => $file->getClientOriginalName(),
                    'paginas' => $analysis['paginas'] ?? null,
                    'columnas_disponibles' => $analysis['columnas'] ?? [],
                    'solo_deteccion' => true,
                ]);
            }
            $columnasSeleccionadas = $this->normalizarColumnasXrf($analysis, $validated['XRF_Columnas']);
            $results = $service->calculateForColumns($analysis, $columnasSeleccionadas);
            $capture = $captureService->generate(
                $file,
                $columnasSeleccionadas,
                true
            );
        } catch (\Throwable $exception) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'Analisis_PDF' => $exception->getMessage(),
            ]);
        }
        return response()->json([
            'archivo' => $file->getClientOriginalName(),
            'paginas' => $analysis['paginas'] ?? null,
            'columnas_disponibles' => $analysis['columnas'] ?? [],
            'columnas_seleccionadas' => $columnasSeleccionadas,
            'resultados' => $results,
            'captura' => $capture,
        ]);
    }

    /** Traduce los errores técnicos de lectura en errores de validación asociados al archivo correcto. */
    private function analizarPdfsXrf(array $archivos, ServicioAnalisisPdfXrf $service): array
    {
        $analisis = [];
        foreach ($archivos as $index => $archivo) {
            try {
                $analisis[] = $service->parseUploadedFile($archivo);
            } catch (\Throwable $exception) {
                Log::warning('No se pudo extraer el PDF XRF.', [
                    'archivo' => $archivo->getClientOriginalName(),
                    'error' => $exception->getMessage(),
                ]);

                throw \Illuminate\Validation\ValidationException::withMessages([
                    "Analisis_PDF.{$index}" => "No se pudo leer {$archivo->getClientOriginalName()}: {$exception->getMessage()}",
                ]);
            }
        }

        return $analisis;
    }

    /** Convierte una incompatibilidad de grado/norma en un mensaje visible junto al selector. */
    private function validarCompatibilidadXrf(
        array $analisis,
        string $nombreEspecificacion,
        string $variable,
        ServicioAnalisisPdfXrf $service
    ): void {
        try {
            $service->assertCompatibleWithNorm($analisis, $nombreEspecificacion, $variable);
        } catch (\RuntimeException $exception) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'Norma_IM.idnormas_im' => $exception->getMessage(),
            ]);
        }
    }

    /** Guarda los PDF originales y agrega su ruta pública a la copia histórica del análisis. */
    private function guardarPdfsXrf(
        array $archivos,
        array &$analisis,
        string $contrato,
        string $numeroReporte
    ): void {
        $contratoSeguro = Str::slug($contrato ?: 'sin-contrato');
        $reporteSeguro = Str::slug($numeroReporte ?: 'sin-reporte');
        $directorio = "public/Reportes/FOR_PIMP_04_02/{$contratoSeguro}/{$reporteSeguro}/Analisis_XRF";

        foreach ($archivos as $index => $archivo) {
            $base = Str::slug(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'analisis-xrf';
            $nombre = $base . '-' . Str::lower(Str::random(8)) . '.pdf';
            $archivo->storeAs($directorio, $nombre);

            if (isset($analisis[$index])) {
                $analisis[$index]['ruta'] = str_replace('public/', 'storage/', $directorio) . '/' . $nombre;
            }
        }
    }

    /** Guarda el PDF único y su captura de tres columnas. */
    private function guardarArchivosColumnasXrf(
        UploadedFile $archivo,
        array &$normaIM,
        string $contrato,
        string $numeroReporte
    ): void {
        $contratoSeguro = Str::slug($contrato ?: 'sin-contrato');
        $reporteSeguro = Str::slug($numeroReporte ?: 'sin-reporte');
        $directorio = "public/Reportes/FOR_PIMP_04_02/{$contratoSeguro}/{$reporteSeguro}/Analisis_XRF";
        $base = Str::slug(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'analisis-xrf';
        $nombrePdf = $base . '-' . Str::lower(Str::random(8)) . '.pdf';
        $dataUrl = (string) ($normaIM['Captura_XRF']['data_url'] ?? '');
        $binary = base64_decode((string) preg_replace('/^data:image\/png;base64,/', '', $dataUrl), true);
        if (!is_string($binary) || $binary === '') {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'Analisis_PDF' => 'No fue posible guardar la captura XRF.',
            ]);
        }

        $archivo->storeAs($directorio, $nombrePdf);
        $normaIM['Analisis_PDF'][0]['ruta'] = str_replace('public/', 'storage/', $directorio) . '/' . $nombrePdf;
        $nombreCaptura = 'captura-columnas-' . implode('-', $normaIM['Columnas_Seleccionadas']) . '-' . Str::lower(Str::random(8)) . '.png';
        Storage::put("{$directorio}/{$nombreCaptura}", $binary);
        $normaIM['Captura_XRF']['ruta'] = str_replace('public/', 'storage/', $directorio) . '/' . $nombreCaptura;
        unset($normaIM['Captura_XRF']['data_url']);
    }

    /** Verifica la regla de dos imágenes por cada disparo marcado manualmente. */
    private function validarFotosDeDisparos(array $imagenes): void
    {
        $conteo = [1 => 0, 2 => 0, 3 => 0];
        foreach ($imagenes as $imagen) {
            if (empty($imagen['es_disparo'])) {
                continue;
            }

            $numero = (int) ($imagen['numero_disparo'] ?? 0);
            if (!isset($conteo[$numero])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'numero_disparo' => 'Selecciona a qué disparo pertenece cada imagen.',
                ]);
            }
            $conteo[$numero]++;
        }

        foreach ($conteo as $numero => $cantidad) {
            if ($cantidad !== 0 && $cantidad !== 2) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'numero_disparo' => "El {$numero}° disparo debe tener exactamente dos imágenes.",
                ]);
            }
        }
    }

    /** Combina imágenes nuevas y existentes antes de validar disparos durante Create y Edit. */
    private function validarDisparosDelRequest(Request $request): void
    {
        $imagesBase64 = $request->input('images_base64', []);
        $existingImages = $request->input('existing_images', []);
        $deletedImages = array_filter($request->input('deleted_images', []), static fn ($index) => $index !== '');
        $esDisparo = $request->input('es_disparo', []);
        $numeroDisparo = $request->input('numero_disparo', []);
        $imagenes = [];

        $indices = array_unique(array_merge(array_keys($imagesBase64), array_keys($existingImages)));
        foreach ($indices as $index) {
            if (in_array((string) $index, array_map('strval', $deletedImages), true)) {
                continue;
            }

            if (empty($imagesBase64[$index]) && empty($existingImages[$index])) {
                continue;
            }

            $imagenes[] = [
                'es_disparo' => !empty($esDisparo[$index]),
                'numero_disparo' => $numeroDisparo[$index] ?? null,
            ];
        }

        $this->validarFotosDeDisparos($imagenes);
    }

    /**
     * Valida en servidor que imágenes automáticas y fotografías manuales no
     * terminen en la misma celda aunque se manipule el formulario del navegador.
     */
    private function validarDistribucionFotosDelRequest(Request $request): void
    {
        $imagesBase64 = $request->input('images_base64', []);
        $existingImages = $request->input('existing_images', []);
        $fotoEsTexto = $request->input('foto_es_texto', []);
        $fotoPaginas = $request->input('foto_pagina', []);
        $fotoPosiciones = $request->input('foto_posicion', []);
        $esDisparo = $request->input('es_disparo', []);
        $eliminadas = array_map(
            'strval',
            array_filter($request->input('deleted_images', []), static fn ($indice) => $indice !== '')
        );

        $automaticas = [];
        if ($request->boolean('Analisis_Imagen_Usar_Reporte')
            && trim((string) $request->input('Analisis_Imagen_Token', '')) !== '') {
            $layoutAnalisis = app(ServicioMetalografiaReporte::class)->normalizarLayoutAnalisis(
                $request->input('Analisis_Reporte_Layout')
            );
            $automaticas[] = array_merge($layoutAnalisis['imagen'], ['etiqueta' => 'Imagen 1 — Micrografía']);
            $automaticas[] = array_merge($layoutAnalisis['resultados'], ['etiqueta' => 'Imagen 2 — Resultados']);
        }
        if ($request->boolean('Patron_Grano.activo') && (int) $request->input('Patron_Grano.id', 0) > 0) {
            $layoutPatron = $this->normalizeFotoLayout(
                $request->input('Patron_Grano.layout.pagina', 1),
                $request->input('Patron_Grano.layout.posicion', 'abajo_izquierda'),
                2
            );
            $automaticas[] = array_merge($layoutPatron, ['etiqueta' => 'Imagen de tamaño de grano']);
        }

        $ocupadas = [];
        foreach ($automaticas as $automatica) {
            $pagina = (int) $automatica['pagina'];
            $posicion = (string) $automatica['posicion'];
            $ocupadas[$pagina] = $ocupadas[$pagina] ?? [];
            if (($posicion === 'pagina_completa' && $ocupadas[$pagina] !== [])
                || in_array('pagina_completa', $ocupadas[$pagina], true)
                || in_array($posicion, $ocupadas[$pagina], true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'foto_posicion' => ($automatica['etiqueta'] ?? 'Un elemento automático') .
                        " repite una posición ocupada en la hoja {$pagina}.",
                ]);
            }
            $ocupadas[$pagina][] = $posicion;
        }

        $indices = array_unique(array_merge(
            array_keys($imagesBase64),
            array_keys($existingImages),
            array_keys($fotoEsTexto)
        ));
        foreach ($indices as $index) {
            if (in_array((string) $index, $eliminadas, true) || !empty($esDisparo[$index])) continue;

            $esExistente = array_key_exists($index, $existingImages);
            if (empty($imagesBase64[$index]) && !$esExistente && empty($fotoEsTexto[$index])) continue;

            $distribucion = $this->normalizeFotoLayout(
                $fotoPaginas[$index] ?? null,
                $fotoPosiciones[$index] ?? null,
                (int) $index
            );
            $pagina = $distribucion['pagina'];
            $posicion = $distribucion['posicion'];
            $ocupadas[$pagina] = $ocupadas[$pagina] ?? [];

            if (($posicion === 'pagina_completa' && $ocupadas[$pagina] !== [])
                || in_array('pagina_completa', $ocupadas[$pagina], true)
                || in_array($posicion, $ocupadas[$pagina], true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'foto_posicion' => "La hoja {$pagina} ya tiene ocupada la posición " .
                        str_replace('_', ' ', $posicion) . '.',
                ]);
            }
            $ocupadas[$pagina][] = $posicion;
        }
    }

    /** Construye la copia histórica de norma, composición, promedios y PDF que conserva el reporte. */
    private function construirNormaIM(Request $request, ?array $normaHistorica = null): ?array
    {
        $idNorma = (int) $request->input('Norma_IM.idnormas_im', 0);
        if ($idNorma <= 0) {
            return null;
        }

        $norma = Normas_IM::find($idNorma);
        if ($norma) {
            $nombreEspecificacion = $norma->Nombre_Espe;
            $variable = $norma->Variable;
            $observaciones = $norma->Observaciones;
            $filasCatalogo = json_decode($norma->Tabla, true);
        } elseif (is_array($normaHistorica)
            && (int) ($normaHistorica['idnormas_im'] ?? 0) === $idNorma) {
            $nombreEspecificacion = $normaHistorica['Nombre_Espe'] ?? '';
            $variable = $normaHistorica['Variable'] ?? '';
            $observaciones = $normaHistorica['Observaciones'] ?? '';
            $filasCatalogo = $normaHistorica['Tabla'] ?? [];
        } else {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'Norma_IM.idnormas_im' => 'La norma seleccionada ya no está disponible.',
            ]);
        }

        $filasCatalogo = is_array($filasCatalogo) ? $filasCatalogo : [];
        $promedios = $request->input('Norma_IM.Promedio', []);
        $mismaNormaHistorica = is_array($normaHistorica)
            && (int) ($normaHistorica['idnormas_im'] ?? 0) === $idNorma;
        $analisisPdf = $mismaNormaHistorica && is_array($normaHistorica['Analisis_PDF'] ?? null)
            ? $normaHistorica['Analisis_PDF'] : [];
        $columnasSeleccionadas = $mismaNormaHistorica ? ($normaHistorica['Columnas_Seleccionadas'] ?? []) : [];
        $capturaXrf = $mismaNormaHistorica ? ($normaHistorica['Captura_XRF'] ?? []) : [];
        $resultadosExtraidos = [];
        $nuevoPdf = $request->hasFile('Analisis_PDF');
        $serviceColumnas = app(ServicioAnalisisColumnasPdfXrf::class);

        if ($nuevoPdf) {
            $columnasSolicitadas = array_values(array_unique(array_map('intval', $request->input('XRF_Columnas', []))));
            if (count($columnasSolicitadas) < 1 || count($columnasSolicitadas) > 3) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'XRF_Columnas' => 'Seleccione entre uno y tres disparos diferentes.',
                ]);
            }
            try {
                $archivo = $request->file('Analisis_PDF');
                $analisis = $serviceColumnas->parseUploadedFile($archivo);
                $columnasSeleccionadas = $this->normalizarColumnasXrf($analisis, $columnasSolicitadas);
                $resultadosExtraidos = $serviceColumnas->calculateForColumns($analisis, $columnasSeleccionadas);
                $capturaXrf = app(ServicioCapturaColumnasPdfXrf::class)->generate(
                    $archivo,
                    $columnasSeleccionadas,
                    true
                );
                $analisisPdf = [$analisis];
            } catch (\Throwable $exception) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'Analisis_PDF' => $exception->getMessage(),
                ]);
            }
        }

        $historicas = [];
        foreach (($normaHistorica['Tabla'] ?? []) as $filaHistorica) {
            if (is_array($filaHistorica)) {
                $historicas[strtolower((string) ($filaHistorica['Elemento'] ?? ''))] = $filaHistorica;
            }
        }

        $filas = [];
        foreach ($filasCatalogo as $indice => $filaCatalogo) {
            if (!is_array($filaCatalogo)) {
                continue;
            }
            $elemento = (string) ($filaCatalogo['Elemento'] ?? '');
            $historica = $historicas[strtolower($elemento)] ?? [];
            $resultado = $resultadosExtraidos[$serviceColumnas->canonicalElement($elemento)] ?? null;
            if ($nuevoPdf) {
                $resuelto = $serviceColumnas->resolveAverage($resultado, (string) ($promedios[$indice] ?? ''));
                $promedio = $resuelto['promedio'];
                $origen = $resuelto['origen'];
            } elseif (($historica['Origen'] ?? '') === 'calculado') {
                $promedio = (string) ($historica['Promedio'] ?? '');
                $origen = 'calculado';
            } else {
                $promedio = trim((string) ($promedios[$indice] ?? ($historica['Promedio'] ?? ($filaCatalogo['Promedio'] ?? ''))));
                $origen = $promedio === '' ? 'pendiente_manual' : 'manual';
            }
            $filas[] = [
                'Elemento' => $elemento,
                'Promedio' => $promedio,
                'Composicion' => (string) ($filaCatalogo['Composicion'] ?? ''),
                'Origen' => $origen,
                'Valores_Seleccionados' => $resultado['valores'] ?? ($historica['Valores_Seleccionados'] ?? []),
            ];
        }

        return [
            'idnormas_im' => $idNorma,
            'Nombre_Espe' => $nombreEspecificacion,
            'Variable' => $variable,
            'Observaciones' => $observaciones,
            'Tabla' => $filas,
            'Analisis_PDF' => $analisisPdf,
            'Columnas_Seleccionadas' => array_values(array_map('intval', $columnasSeleccionadas)),
            'Captura_XRF' => is_array($capturaXrf) ? $capturaXrf : [],
        ];
    }

    /** Valida hasta diez lecturas y recalcula el promedio en servidor para no confiar en JavaScript. */
    private function guardarPromedioDureza(Request $request, array &$datosEquipo): void
    {
        $valoresRecibidos = $request->input('Datos_Equipo.VALORES_DUREZA', []);
        $valoresRecibidos = is_array($valoresRecibidos)
            ? array_slice(array_values($valoresRecibidos), 0, 10)
            : [];
        $valoresGuardados = [];
        $valoresNumericos = [];

        for ($index = 0; $index < 10; $index++) {
            $valor = trim((string) ($valoresRecibidos[$index] ?? ''));
            $valoresGuardados[] = $valor;

            // Vacío o de uno a tres guiones significa que la medición no fue realizada.
            if ($valor === '' || preg_match('/^-{1,3}$/', $valor)) {
                continue;
            }

            $valorNormalizado = str_replace(',', '.', $valor);
            if (!preg_match('/^(?:\d+(?:\.\d*)?|\.\d+)$/', $valorNormalizado)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "Datos_Equipo.VALORES_DUREZA.{$index}" => 'Capture un valor numérico mayor o igual a cero, deje el campo vacío o utilice hasta tres guiones.',
                ]);
            }

            $valoresNumericos[] = (float) $valorNormalizado;
        }

        $datosEquipo['VALORES_DUREZA'] = $valoresGuardados;
        $datosEquipo['PROMEDIO_DUREZA'] = empty($valoresNumericos)
            ? ''
            : number_format(
                round(array_sum($valoresNumericos) / count($valoresNumericos), 2, PHP_ROUND_HALF_UP),
                2,
                '.',
                ''
            );
    }

    public function Datos_QR($datosParaCrearQR)
    {
        $Contrato = $datosParaCrearQR['Contrato'] ?? 'SinContrato';
        $No_Reporte = $datosParaCrearQR['No_Reporte'] ?? 'SinReporte';
        $token = $datosParaCrearQR['qr_token'] ?? null;

        $idsConsumibles = array_filter([
            $datosParaCrearQR['idEquipo'] ?? null,
            $datosParaCrearQR['idEquipo1'] ?? null,
            $datosParaCrearQR['idSonda'] ?? null,
            $datosParaCrearQR['idBlock'] ?? null
        ]);

        /*
        |--------------------------------------------------------------------------
        | OBTENER FACTURAS Y CERTIFICADOS
        |--------------------------------------------------------------------------
        */

        $facturas = general_eyc::whereIn('idGeneral_EyC', $idsConsumibles)
            ->whereNotNull('Factura')
            ->pluck('Factura')
            ->toArray();

        $certificados = certificados::whereIn('idGeneral_EyC', $idsConsumibles)
            ->whereNotNull('Certificado_Actual')
            ->pluck('Certificado_Actual')
            ->toArray();

        $todasLasRutas = array_values(array_merge($facturas, $certificados));

        /*
        |--------------------------------------------------------------------------
        | FILTRAR RUTAS INVALIDAS
        |--------------------------------------------------------------------------
        */

        $rutasInvalidas = ['EN ESPERA DE DATOS', 'ESPERA DE DATO', 'N/A'];

        $rutasValidas = array_filter($todasLasRutas, function ($ruta) use ($rutasInvalidas) {
            if (!$ruta) {
                return false;
            }

            return !in_array(trim(strtoupper($ruta)), $rutasInvalidas);
        });

        /*
        |--------------------------------------------------------------------------
        | DIRECTORIO TEMPORAL
        |--------------------------------------------------------------------------
        */

        $directorioTemporal = storage_path("app/temp_pdfs/FOR_PIMP_04_02/{$Contrato}/{$No_Reporte}");

        if (!File::exists($directorioTemporal)) {
            File::makeDirectory($directorioTemporal, 0777, true);
        }

        $pdfsTemporales = [];

        /*
        |--------------------------------------------------------------------------
        | COPIAR PDFs TEMPORALES
        |--------------------------------------------------------------------------
        */

        foreach ($rutasValidas as $rutaPdf) {
            $rutaOriginal = storage_path('app/public/' . $rutaPdf);

            if (!File::exists($rutaOriginal)) {
                Log::warning('PDF no encontrado', ['ruta' => $rutaOriginal]);
                continue;
            }

            $nombreArchivo = basename($rutaOriginal);
            $rutaTemporal = $directorioTemporal . DIRECTORY_SEPARATOR . $nombreArchivo;

            File::copy($rutaOriginal, $rutaTemporal);
            $pdfsTemporales[] = $rutaTemporal;
        }

        /*
        |--------------------------------------------------------------------------
        | GENERAR TOKEN QR PUBLICO
        |--------------------------------------------------------------------------
        */

        $rutaPublicaPdf = route('qr.reporte', ['token' => $token]);
        $nombreQR = "QR_{$Contrato}_{$No_Reporte}.svg";
        $directorioQR = storage_path("app/public/Reportes/FOR_PIMP_04_02/{$Contrato}/{$No_Reporte}/QR_REPORTES");

        if (!File::exists($directorioQR)) {
            File::makeDirectory($directorioQR, 0777, true);
        }

        $rutaQrCompleta = $directorioQR . DIRECTORY_SEPARATOR . $nombreQR;

        \QrCode::format('svg')
            ->size(300)
            ->margin(0)
            ->generate($rutaPublicaPdf, $rutaQrCompleta);

        $rutaQrPublica = "storage/Reportes/FOR_PIMP_04_02/{$Contrato}/{$No_Reporte}/QR_REPORTES/" . $nombreQR;

        /*
        |--------------------------------------------------------------------------
        | VALIDAR PDFs
        |--------------------------------------------------------------------------
        */

        if (empty($pdfsTemporales)) {
            Log::warning('No hay PDFs válidos para unir.');

            return [
                'pdf' => null,
                'qr' => $rutaQrPublica
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | UNIR PDFs
        |--------------------------------------------------------------------------
        */

        $pdf = new Fpdi();

        foreach ($pdfsTemporales as $archivoPdf) {
            try {
                /*
                |--------------------------------------------------------------------------
                | HACER PDF COMPATIBLE CON FPDI
                |--------------------------------------------------------------------------
                */

                $archivoCompatible = str_replace('.pdf', '_compatible.pdf', $archivoPdf);

                $comando =
                    'gswin64c -sDEVICE=pdfwrite '
                    . '-dCompatibilityLevel=1.4 '
                    . '-dNOPAUSE '
                    . '-dQUIET '
                    . '-dBATCH '
                    . '-sOutputFile="'
                    . $archivoCompatible
                    . '" "'
                    . $archivoPdf
                    . '"';

                exec($comando);

                $cantidadPaginas = $pdf->setSourceFile($archivoCompatible);

                for ($pagina = 1; $pagina <= $cantidadPaginas; $pagina++) {
                    $template = $pdf->importPage($pagina);
                    $size = $pdf->getTemplateSize($template);
                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($template);
                }
            } catch (\Exception $e) {
                Log::error('Error procesando PDF', [
                    'archivo' => $archivoPdf,
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        $directorioFinal = "Reportes/FOR_PIMP_04_02/{$Contrato}/{$No_Reporte}/";
        $rutaDirectorioFinal = storage_path("app/public/" . $directorioFinal);

        if (!File::exists($rutaDirectorioFinal)) {
            File::makeDirectory($rutaDirectorioFinal, 0777, true);
        }

        $nombreArchivoFinal = "QR_FOR_PIMP_04_02_{$Contrato}_{$No_Reporte}.pdf";
        $rutaPdfFinal = $rutaDirectorioFinal . $nombreArchivoFinal;

        $pdf->Output($rutaPdfFinal, 'F');

        File::deleteDirectory($directorioTemporal);

        return [
            'pdf' => "storage/" . $directorioFinal . $nombreArchivoFinal,
            'qr' => $rutaQrPublica
        ];
    }


    public function OS_OC($datosParaCrearOS_OC)
    {
        $idPrueba_Aplica = $datosParaCrearOS_OC['idPrueba_Aplica'];
        $Cliente = $datosParaCrearOS_OC['Cliente'];
        $Lugar = $datosParaCrearOS_OC['Lugar'] ?? $datosParaCrearOS_OC['Instalacion'] ?? 'ESPERA DE DATOS';
        $Contrato= $datosParaCrearOS_OC['Contrato'];
        //$Contrato = trim(strtoupper($datosParaCrearOS_OC['Contrato']));
        $Proyecto = $datosParaCrearOS_OC['Proyecto'];
        $Material = $datosParaCrearOS_OC['Material'];
        $Isometrico_Plano = $datosParaCrearOS_OC['Isometrico_Plano'] ?? $datosParaCrearOS_OC['No_Isometrico'] ?? 'ESPERA DE DATOS';
        $ResultadosJuntas = $datosParaCrearOS_OC['ResultadosJuntas'];
        $idSolicitud = $datosParaCrearOS_OC['idSolicitud'];
        $idReportes = $datosParaCrearOS_OC['idReportes'];

        $Orden_Servicio = new Orden_Servicio;
        $Orden_Servicio_Prueba = new Orden_Servicio_Prueba;
        $Firmantes_OS = new Firmantes_OS;
        $Grupo_Juntas_Detalles_OS = new Grupo_Juntas_Detalles_OS;
        $OC = new OC;
        $Detalles_OC = new detallesOC;
        $Lineal_Ideal = new Lineal_Ideal;

        $BusquedaCliente = clientes::where('Cliente', 'like', '%' . $Cliente . '%')->first();

        if ($BusquedaCliente) {
            $idCliente = $BusquedaCliente->idClientes; // O el campo que sea clave primaria
            //$nombreReal = $BusquedaCliente->Cliente; // Nombre exacto encontrado
            $BusquedaContratoOS = Orden_Servicio::where('Contrato', $Contrato)->first();

            if($BusquedaContratoOS)
            {
                $idOrdenServicio = $BusquedaContratoOS->idOrden_Servicio;
            } else{
            $Orden_Servicio->idClientes = $idCliente;
            $Orden_Servicio->Fecha = '2001/01/01';
            $Orden_Servicio->Lugar = $Lugar;
            $Orden_Servicio->Contrato = $Contrato;
            $Orden_Servicio->Proyecto_actividad = $Proyecto;
            $Orden_Servicio->Material = $Material;
            $Orden_Servicio->Plano_isometrico = $Isometrico_Plano;
            $Orden_Servicio->save();

            // Obtén el ID del registro recién creado
            $idOrdenServicio = $Orden_Servicio->idOrden_Servicio;

            $Orden_Servicio_Prueba->idOrden_Servicio = $idOrdenServicio;
            $Orden_Servicio_Prueba->idPrueba_Aplica = $idPrueba_Aplica;
            $Orden_Servicio_Prueba->save();

            $Firmantes_OS->idOrden_Servicio = $idOrdenServicio;
            $Firmantes_OS->Nombre_Cargo = '[]';
            $Firmantes_OS->save();

            $Grupo_Juntas_Detalles_OS->idOrden_Servicio = $idOrdenServicio;
            $Grupo_Juntas_Detalles_OS->Juntas_grupo = $ResultadosJuntas;
            $Grupo_Juntas_Detalles_OS->save();

            }

            $BusquedaContratoOC = OC::where('Contrato', $Contrato)->first();

            if($BusquedaContratoOC)
            {
                $idOC = $BusquedaContratoOC->idOC;
            } else{
            $EsperaDato = "ESPERA DE DATOS";
            $OC->Contrato = $Contrato;
            $OC->Num_OC = $EsperaDato;
            $OC->Requisicion = $EsperaDato;
            $OC->Proyecto = $Proyecto;
            $OC->Lugar_trabajo = $EsperaDato;
            $OC->Fecha_Solicitud = '2001/01/01';
            $OC->Tipo_Servicio = $EsperaDato;
            $OC->Estatus = 'OC';
            $OC->OC_archivo = $EsperaDato;
            $OC->save();

            $idOC = $OC->idOC;
            $Detalles_OC->idOC = $idOC;
            $Detalles_OC->Detalles = $EsperaDato;
            $Detalles_OC->save();
            }
            
            $Lineal_Ideal->idOC = $idOC;
            $Lineal_Ideal->idOrden_Servicio = $idOrdenServicio;
            $Lineal_Ideal->idSolicitud = $idSolicitud;
            $Lineal_Ideal->idReportes = $idReportes;
            $Lineal_Ideal->Estatus = 'CREADO';
            $Lineal_Ideal->save();

        } else {
            // Cliente no encontrado
            $Cliente = "POR DEFINIR";
            $Busqueda2Cliente = clientes::where('Cliente', $Cliente)->first();
            // Si no existe, crea el cliente "POR DEFINIR"
            if (!$Busqueda2Cliente) {
                $Busqueda2Cliente = new clientes();
                $Busqueda2Cliente->Cliente = $Cliente;
                $Busqueda2Cliente->RFC = 'N/A';
                $Busqueda2Cliente->Telefono = 'N/A';
                $Busqueda2Cliente->Correo = 'N/A';
                $Busqueda2Cliente->save();
            }

            $BusquedaContratoOS = Orden_Servicio::where('Contrato', $Contrato)->first();

            if($BusquedaContratoOS)
            {
                $idOrdenServicio = $BusquedaContratoOS->idOrden_Servicio;
            } else{
            // Obtén el ID del cliente "POR DEFINIR"
            $idClientes = $Busqueda2Cliente->idClientes;
            $Orden_Servicio->idClientes = $idClientes;
            $Orden_Servicio->Fecha = '2001/01/01';
            $Orden_Servicio->Lugar = $Lugar;
            $Orden_Servicio->Contrato = $Contrato;
            $Orden_Servicio->Proyecto_actividad = $Proyecto;
            $Orden_Servicio->Material = $Material;
            $Orden_Servicio->Plano_isometrico = $Isometrico_Plano;
            $Orden_Servicio->save();

            // Obtén el ID del registro recién creado
            $idOrdenServicio = $Orden_Servicio->idOrden_Servicio;

            $Orden_Servicio_Prueba->idOrden_Servicio = $idOrdenServicio;
            $Orden_Servicio_Prueba->idPrueba_Aplica = $idPrueba_Aplica;
            $Orden_Servicio_Prueba->save();

            $Firmantes_OS->idOrden_Servicio = $idOrdenServicio;
            $Firmantes_OS->Nombre_Cargo = '[]';
            $Firmantes_OS->save();

            $Grupo_Juntas_Detalles_OS->idOrden_Servicio = $idOrdenServicio;
            $Grupo_Juntas_Detalles_OS->Juntas_grupo = $ResultadosJuntas;
            $Grupo_Juntas_Detalles_OS->save();

            }

            $BusquedaContratoOC = OC::where('Contrato', $Contrato)->first();

            if($BusquedaContratoOC)
            {
                $idOC = $BusquedaContratoOC->idOC;
            } else{
            $EsperaDato = "ESPERA DE DATOS";
            $OC->Contrato = $Contrato;
            $OC->Num_OC = $EsperaDato;
            $OC->Requisicion = $EsperaDato;
            $OC->Proyecto = $Proyecto;
            $OC->Lugar_trabajo = $EsperaDato;
            $OC->Fecha_Solicitud = '2001/01/01';
            $OC->Tipo_Servicio = $EsperaDato;
            $OC->Estatus = 'OC';
            $OC->OC_archivo = $EsperaDato;
            $OC->save();

            $idOC = $OC->idOC;
            $Detalles_OC->idOC = $idOC;
            $Detalles_OC->Detalles = $EsperaDato;
            $Detalles_OC->save();
            }

            $Lineal_Ideal->idOC = $idOC;
            $Lineal_Ideal->idOrden_Servicio = $idOrdenServicio;
            $Lineal_Ideal->idSolicitud = $idSolicitud;
            $Lineal_Ideal->idReportes = $idReportes;
            $Lineal_Ideal->Estatus = 'CREADO';
            $Lineal_Ideal->save();
        }

    }

    public function FOR_PIMP_04_02_store(Request $request, ServicioAnalisisImagenImageJ $servicioImagen)
    {
        $Estatus = "CREADO";
        // Validar los Detalles_Generales
        $validatedData = $request->validate([
            /*DETALLES GENERALES */
            'Detalles_Generales' => 'required|array',  // Asegura que es un array
            'Detalles_Generales.Fecha' => 'nullable|date',
            'Detalles_Generales.No_Reporte' => 'nullable|string',
            'Detalles_Generales.Cliente' => 'nullable|string',
            'Detalles_Generales.Contrato' => 'nullable|string',
            'Detalles_Generales.Proyecto' => 'nullable|string',
            'Detalles_Generales.Orden_Trabajo' => 'nullable|string',
            'Detalles_Generales.Folio' => 'nullable|string',
            'Detalles_Generales.Partida' => 'nullable|string',
            'Detalles_Generales.Instalacion' => 'nullable|string',
            'Detalles_Generales.No_Isometrico' => 'nullable|string',
            'Detalles_Generales.Nombre_Pieza' => 'nullable|string',
            'Detalles_Generales.Criterio_Evaluacion' => 'nullable|string',
            'Detalles_Generales.Accesorio' => 'nullable|string',
            'Detalles_Generales.Tuberia' => 'nullable|string',
            'Detalles_Generales.Estructural' => 'nullable|string',
            'Detalles_Generales.No_Isometrico_Plano' => 'nullable|string',
            'Detalles_Generales.Observaciones_Notas' => 'nullable|string',
            'Detalles_Generales.Material' => 'nullable|string',
            'Detalles_Generales.Trazabilidad' => 'nullable|string',
            'Detalles_Generales.Procedimiento' => 'nullable|string',
            'Detalles_Generales.idSolicitud' => 'nullable|string',
            'Detalles_Generales.Num_Soldador' => 'nullable|string',
            'Detalles_Generales.Nombre_Soldador' => 'nullable|string',
            'Detalles_Generales.Reporte_Firmado' => 'nullable|file|mimes:pdf|max:20480',
            'TieneCliente' => 'required|in:si,no',
            'ClienteSelect' => 'nullable|required_if:TieneCliente,si|string|max:255',
            'ClienteInput' => 'nullable|required_if:TieneCliente,no|string|max:255',
            'TieneContrato' => 'required|in:si,no',
            // Datos generados por los dos componentes de análisis de imagen.
            'Analisis_Imagen_Token' => 'nullable|uuid',
            'Analisis_Imagen_Usar_Reporte' => 'nullable|boolean',
            'Analisis_Reporte_Comentario_Imagen' => 'nullable|string|max:500',
            'Analisis_Reporte_Descripcion' => 'nullable|string|max:20000',
            'Analisis_Reporte_Layout' => 'nullable|array',
            'Analisis_Reporte_Layout.*.pagina' => 'nullable|integer|min:1|max:999',
            'Analisis_Reporte_Layout.*.posicion' => 'nullable|in:arriba_izquierda,arriba_derecha,abajo_izquierda,abajo_derecha,pagina_completa',
            'Conteo_Granos_JSON' => 'nullable|json|max:100000',
            
            /*DATOS DEL EQUIPO Y OBSERVACIONES*/
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string',
            'Datos_Equipo.NS_EQUIPO' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO' => 'nullable|string',

            'Datos_Equipo.MARCA_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.NS_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO1' => 'nullable|string',

            'Datos_Equipo.Observaciones' => 'nullable|string',
            'Datos_Equipo.ESCALA_DUREZA' => 'nullable|string|max:50',
            'Datos_Equipo.VALORES_DUREZA' => 'nullable|array|max:10',
            'Datos_Equipo.VALORES_DUREZA.*' => 'nullable|string|max:30',
            'Datos_Equipo.PROMEDIO_DUREZA' => 'nullable|string|max:30',
            'Datos_Equipo.DESCRIPCION_MATERIAL' => 'nullable|string|max:255',
            'Datos_Equipo.DUREZA_BRINELL' => 'nullable|string|max:100',
            'Datos_Equipo.RESISTENCIA_TENSION' => 'nullable|string|max:100',
            'Datos_Equipo.RESISTENCIA_CEDENCIA' => 'nullable|string|max:100',
            'Datos_Equipo.TAMANO_GRANO' => 'nullable|string|max:100',
            'Datos_Equipo.NORMA_REFERENCIA' => 'nullable|string|max:255',
            'Datos_Equipo.DUREZA_BRINELL_MAX' => 'nullable|string|max:100',
            'Datos_Equipo.RESISTENCIA_TENSION_MIN' => 'nullable|string|max:100',
            'Datos_Equipo.RESISTENCIA_CEDENCIA_ESPECIFICADA' => 'nullable|string|max:100',
            'Datos_Equipo.RESISTENCIA_TENSION_MAX' => 'nullable|string|max:100',
            'Datos_Equipo.MATERIAL_PANO' => 'nullable|string|max:255',
            'Datos_Equipo.MATERIAL_ABRASIVO' => 'nullable|string|max:255',
            'Datos_Equipo.REACTIVO' => 'nullable|string|max:255',
            'Datos_Equipo.TIEMPO_ATAQUE' => 'nullable|string|max:100',
            'Datos_Equipo.FASES_PRESENTES' => 'nullable|string|max:1000',
            'Datos_Equipo.ESPECIFICACION_MATERIAL' => 'nullable|string|max:1000',
            'Datos_Equipo.QR_TOKEN' => 'nullable|string',
            'Datos_Equipo.QR_PDF' => 'nullable|string',
            'Datos_Equipo.PDF_UNIFICADO' => 'nullable|string',

            'Norma_IM' => 'nullable|array',
            'Norma_IM.idnormas_im' => 'nullable|required_with:Analisis_PDF|integer|exists:Normas_IM,idnormas_im',
            'Norma_IM.Promedio' => 'nullable|array',
            'Norma_IM.Promedio.*' => 'nullable|string|max:255',
            'Analisis_PDF' => 'nullable|file|mimes:pdf|max:10240',
            'XRF_Columnas' => 'nullable|required_with:Analisis_PDF|array|min:1|max:3',
            'XRF_Columnas.*' => 'required_with:Analisis_PDF|integer|distinct|between:1,20',
            'Patron_Grano' => 'nullable|array',
            'Patron_Grano.id' => 'nullable|required_if:Patron_Grano.activo,1|integer|min:1',
            'Patron_Grano.descripcion' => 'nullable|required_with:Patron_Grano.id|string|max:500',
            'Patron_Grano.activo' => 'nullable|boolean',
            'Patron_Grano.usar_version_catalogo' => 'nullable|boolean',
            'Patron_Grano.layout' => 'nullable|array',
            'Patron_Grano.layout.pagina' => 'nullable|integer|min:1|max:999',
            'Patron_Grano.layout.posicion' => 'nullable|in:arriba_izquierda,arriba_derecha,abajo_izquierda,abajo_derecha,pagina_completa',

            //Validar el campo NumFirmas
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string|max:5000',
            'foto_es_texto' => 'nullable|array',
            'foto_es_texto.*' => 'nullable|boolean',
            'foto_pagina' => 'nullable|array',
            'foto_pagina.*' => 'nullable|integer|min:1',
            'foto_posicion' => 'nullable|array',
            'foto_posicion.*' => 'nullable|in:arriba_izquierda,arriba_derecha,abajo_izquierda,abajo_derecha,pagina_completa',
            'es_disparo' => 'nullable|array',
            'es_disparo.*' => 'nullable|boolean',
            'numero_disparo' => 'nullable|array',
            'numero_disparo.*' => 'nullable|in:1,2,3',
            'numFirmas' => 'required|integer|in:1,2,3,4',

            /*1 FIRMAS */
            'Firmas_Reportes1' => 'required|array',  // Asegura que es un array

            'Firmas_Reportes1.Realizo' => 'nullable|string',
            'Firmas_Reportes1.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.EMPRESA_TECNICO' => 'nullable|string',

            /*2 FIRMAS */
            'Firmas_Reportes2' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes2.Realizo' => 'nullable|string',
            'Firmas_Reportes2.Vobo1' => 'nullable|string',

            'Firmas_Reportes2.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.NOMBRE_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes2.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.PUESTO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes2.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.EMPRESA_ENCARGADO' => 'nullable|string',

            /*3 FIRMAS */
            'Firmas_Reportes3' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes3.Realizo' => 'nullable|string',
            'Firmas_Reportes3.Vobo1' => 'nullable|string',
            'Firmas_Reportes3.Vobo2' => 'nullable|string',

            'Firmas_Reportes3.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.NOMBRE_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.NOMBRE_2DO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes3.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.PUESTO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.PUESTO_2DO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes3.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.EMPRESA_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.EMPRESA_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.NUMERO_FICHA' => 'nullable|string',

            /*4 FIRMAS */
            'Firmas_Reportes4' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes4.Realizo' => 'nullable|string',
            'Firmas_Reportes4.Vobo1' => 'nullable|string',
            'Firmas_Reportes4.Vobo2' => 'nullable|string',
            'Firmas_Reportes4.Vobo3' => 'nullable|string',

            'Firmas_Reportes4.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_3RO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes4.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_3RO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes4.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_3RO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.NUMERO_FICHA' => 'nullable|string',
        ]);

        // La validación considera juntas las imágenes automáticas y las fotografías manuales.
        $this->validarDistribucionFotosDelRequest($request);
        /*Detalles Generales y Datos del Equipo */
        $Reportes = new reporte();  // Modelo de la tabla donde guardas los datos
        $Grupo_Juntas_Detalles_Re = new Grupo_Juntas_Detalles_Re();  // Modelo de la tabla donde guardas los datos
        $Firmas_Reportes = new Firma_Reporte();  // Modelo de la tabla donde guardas los datos
        $Fotos_Reportes = new Fotos_Reporte();  // Modelo de la tabla donde guardas los datos
        $idPrueba_Aplica = $request->input('idPrueba_Aplica');

        $Reportes->idPrueba_Aplica = $idPrueba_Aplica;
        
        // ==========================
        // Lógica para manejar Cliente
        // ==========================
        if ($request->TieneCliente === 'si') {
            $validatedData['Detalles_Generales']['Cliente'] = $request->ClienteSelect;
        } else {
            $validatedData['Detalles_Generales']['Cliente'] = $request->ClienteInput;
        }
        // ==========================
        // Lógica para manejar Contrato
        // ==========================
        // Lógica para manejar el campo Contrato
        if ($request->TieneContrato === "no") {

            // Si el usuario alteró el valor o no llegó, se recalcula en backend
            $actual = $request->Detalles_Generales['Contrato'] ?? null;

            // Verificar que realmente tenga el formato correcto
            if (!$actual || !preg_match('/^AICO-INT-\d{4}$/', $actual)) {

                // Seguridad: volver a calcular el consecutivo
                $registros = reporte::orderBy('idReportes', 'DESC')->get();
                $ultimoNumero = 0;

                foreach ($registros as $r) {
                    $json = json_decode($r->Detalles_Generales, true);

                    if (!empty($json['Contrato']) && str_starts_with($json['Contrato'], 'AICO-INT-')) {
                        $n = intval(str_replace('AICO-INT-', '', $json['Contrato']));
                        if ($n > $ultimoNumero) $ultimoNumero = $n;
                        break;
                    }
                }

                $nuevo = "AICO-INT-" . str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);

                $validatedData['Detalles_Generales']['Contrato'] = $nuevo;

            } else {
                // Si el frontend envió un contrato válido, se utiliza ese
                $validatedData['Detalles_Generales']['Contrato'] = $actual;
            }
        }

        // El servidor recalcula dureza y congela la norma/XRF dentro del JSON del reporte.
        $this->guardarPromedioDureza($request, $validatedData['Datos_Equipo']);

        $normaIM = $this->construirNormaIM($request);
        if ($normaIM !== null) {
            if ($request->hasFile('Analisis_PDF')) {
                $this->guardarArchivosColumnasXrf(
                    $request->file('Analisis_PDF'),
                    $normaIM,
                    (string) ($validatedData['Detalles_Generales']['Contrato'] ?? ''),
                    (string) ($validatedData['Detalles_Generales']['No_Reporte'] ?? '')
                );
            }
            $validatedData['Detalles_Generales']['Norma_IM'] = $normaIM;
        }

        // La imagen 3 se copia al expediente antes de guardar para aislarla de futuras ediciones del catálogo.
        $servicioPatronGrano = app(ServicioPatronGranoReporte::class);
        $patronGrano = $servicioPatronGrano->construirHistorico(
            $request,
            'FOR_PIMP_04_02',
            (string) ($validatedData['Detalles_Generales']['Contrato'] ?? ''),
            (string) ($validatedData['Detalles_Generales']['No_Reporte'] ?? '')
        );
        if ($patronGrano !== null) {
            $validatedData['Detalles_Generales']['PATRON_GRANO'] = $patronGrano;
        }

        // Adjunta únicamente un análisis previamente generado por el mismo usuario autenticado.
        if (!empty($validatedData['Analisis_Imagen_Token'])) {
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN'] = $servicioImagen->obtenerPorToken(
                $validatedData['Analisis_Imagen_Token'],
                (int) Auth::id()
            );
            // La decisión de incluirlo en el PDF pertenece al reporte, no al resultado global de Fiji.
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['usar_en_reporte'] =
                !empty($validatedData['Analisis_Imagen_Usar_Reporte']);
            // Guarda el pie de fotografía de la Imagen 1 sin mezclarlo con los resultados de la Imagen 2.
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['comentario_imagen_reporte'] =
                trim((string) ($validatedData['Analisis_Reporte_Comentario_Imagen'] ?? ''));
            // Se conserva exactamente el texto que el técnico revisó en la sección FOTOS.
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['descripcion_reporte'] =
                trim((string) ($validatedData['Analisis_Reporte_Descripcion'] ?? ''));
            // Imagen 1 e Imagen 2 conservan la celda que el técnico eligió en FOTOS.
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['layout_reporte'] =
                app(ServicioMetalografiaReporte::class)->normalizarLayoutAnalisis(
                    $validatedData['Analisis_Reporte_Layout'] ?? []
                );
        }
        // Guarda líneas, cruces y resumen recalculado dentro de Detalles_Generales.
        $conteoGranos = $this->normalizarConteoGranos($validatedData['Conteo_Granos_JSON'] ?? null);
        if ($conteoGranos !== null) {
            $validatedData['Detalles_Generales']['CONTEO_GRANOS'] = $conteoGranos;
        }

        // Guardar Detalles_Generales como JSON en la base de datos
        $Reportes->Detalles_Generales = json_encode($validatedData['Detalles_Generales']);
        // Guardar Datos_Equipo como JSON en la base de datos
        $Reportes->Datos_Equipo = json_encode($validatedData['Datos_Equipo']);

        $Reportes->Estatus = $Estatus; // Asignar el estatus

        // Guardar el registro en la base de datos   
        $Reportes->save();

        /*
        |--------------------------------------------------------------------------
        | DATOS PARA CREAR PDF + QR
        |--------------------------------------------------------------------------
        */

        $validatedData['Datos_Equipo']['QR_TOKEN'] = $validatedData['Datos_Equipo']['QR_TOKEN'] ?? (string) Str::uuid();

        $datosParaCrearQR = [
            'Contrato' => $validatedData['Detalles_Generales']['Contrato'] ?? null,
            'No_Reporte' => $validatedData['Detalles_Generales']['No_Reporte'] ?? null,
            'qr_token' => $validatedData['Datos_Equipo']['QR_TOKEN'],
            'idEquipo' => $validatedData['Datos_Equipo']['ID_EQUIPO'] ?? null,
            'idEquipo1' => $validatedData['Datos_Equipo']['ID_EQUIPO1'] ?? null,
            'idSonda' => $validatedData['Datos_Equipo']['ID_SONDA'] ?? null,
            'idBlock' => $validatedData['Datos_Equipo']['ID_BLOCK'] ?? null,
        ];

        /*
        |--------------------------------------------------------------------------
        | GENERAR PDF + QR
        |--------------------------------------------------------------------------
        */

        $resultadoQR = $this->Datos_QR($datosParaCrearQR);
        $validatedData['Datos_Equipo']['QR_PDF'] = $resultadoQR['qr'] ?? null;
        $validatedData['Datos_Equipo']['PDF_UNIFICADO'] = $resultadoQR['pdf'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | GUARDAR RUTAS EN DATOS_EQUIPO
        |--------------------------------------------------------------------------
        */

        $Reportes->Datos_Equipo = json_encode($validatedData['Datos_Equipo']);
        $Reportes->save();
        
        // Obtener el idReportes del registro recién creado
        $idReportes = $Reportes->idReportes;
        $Grupo_Juntas_Detalles_Re->idReportes = $idReportes;

        $titulos_json = $request->input('titulos_data', '[]');
        $titulos = json_decode($titulos_json, true); // array asociativo
        $datosAgrupados = [];
        
        // 1. Procesar filas SIN título (si existen)
        $sinTituloKey = 'sin_titulo';
        $filasSinTitulo = $request->input("no.$sinTituloKey", []);
        //$longitudesSin = $request->input("Long_Inspecc.$sinTituloKey", []);
        $numFilasSin = count($filasSinTitulo);//agregar

        // 🔹 cuántas filas debe tener cada bloque
        $maxFilasPorBloque = 21; //Agregar 1 + que en create y edit para que la longitud entre en el mismo bloque

        $bloques = []; //agregar
        $bloqueActual = [];//agregar
        $contador = 0;//agregar
        /*//agregar
        |--------------------------------------------------------------------------
        | FUNCIONES AUXILIARES
        |--------------------------------------------------------------------------
        */
        $cerrarBloque = function () use (&$bloques, &$bloqueActual, &$contador) {
            if (!empty($bloqueActual)) {
                $bloques[] = $bloqueActual;
                $bloqueActual = [];
                $contador = 0;
            }
        };

        $agregarElemento = function ($elemento) use (&$bloques, &$bloqueActual, &$contador, $maxFilasPorBloque) {
            if ($contador >= $maxFilasPorBloque) {
                $bloques[] = $bloqueActual;
                $bloqueActual = [];
                $contador = 0;
            }

            $bloqueActual[] = $elemento;
            $contador++;
        };


        /*Firmas */
        // Guardar las firmas
        $numFirmas = $request->input('numFirmas'); // Obtener el número de firmas seleccionadas
        
        if ($numFirmas == 1) {
            $validatedData['Firmas_Reportes1']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes1']);
        }
        else if ($numFirmas == 2) {
            $validatedData['Firmas_Reportes2']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes2']);
        }
        else if ($numFirmas == 3) {
            $validatedData['Firmas_Reportes3']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes3']);
        }
        else{
            $validatedData['Firmas_Reportes4']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes4']);
        }

        $Firmas_Reportes->idReportes = $idReportes;
        $Firmas_Reportes->save();

        /* Fotos, cuadros de texto, distribución manual y pares de imágenes de disparos. */
        $imagesBase64 = $request->input('images_base64', []);
        $esDisparo = $request->input('es_disparo', []);
        $numeroDisparo = $request->input('numero_disparo', []);
        $fotoPaginas = $request->input('foto_pagina', []);
        $fotoPosiciones = $request->input('foto_posicion', []);
        $fotoEsTexto = $request->input('foto_es_texto', []);
        $comentariosFoto = $request->input('comments', []);
        $hayElementosFoto = !empty(array_filter($imagesBase64)) || !empty(array_filter($fotoEsTexto));
        if($hayElementosFoto)
        {
        $imagenesGuardadas = []; // Para almacenar rutas de imágenes guardadas

        foreach ($imagesBase64 as $index => $base64Image) {
            $esCuadroTexto = !empty($fotoEsTexto[$index]);
            if (empty($base64Image) && !$esCuadroTexto) {
                continue;
            }

            $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
            $Contrato = $validatedData['Detalles_Generales']['Contrato'];

            // Decodificar Base64
            $rutaPublicaFoto = null;
            if (!$esCuadroTexto) {
            $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));

            // Nombre único
            $imageName = 'imagen_' . time() . '_' . $index . '.png';

            $rutaCarpeta = "public/Reportes/FOR_PIMP_04_02/{$Contrato}/{$No_Reporte}/Fotos";

            Storage::put("{$rutaCarpeta}/{$imageName}", $image);
            $rutaPublicaFoto = "storage/Reportes/FOR_PIMP_04_02/{$Contrato}/{$No_Reporte}/Fotos/{$imageName}";
            }

            // Los disparos ignoran página completa; las fotos normales respetan el cuadrante elegido.
            $distribucionFoto = $this->normalizeFotoLayout(
                $fotoPaginas[$index] ?? null,
                $fotoPosiciones[$index] ?? null,
                $index
            );

            // ✔ Detalles Junta activado
            $detallesJunta = isset($request->detalles_junta_check[$index]) 
                                ? (bool)$request->detalles_junta_check[$index] 
                                : false;

            // Si detalles junta está activo, guardar datos
            $datosJunta = null;

            if ($detallesJunta) {
                $datosJunta = [
                    'junta' => $request->junta[$index] ?? null,
                    'no_indicacion' => $request->no_indicacion[$index] ?? null,
                    'tipo_indicacion' => $request->tipo_indicacion[$index] ?? null,
                    'longitud' => $request->longitud[$index] ?? null,
                    'profundidad' => $request->profundidad[$index] ?? null,
                    'nivel_referencia' => $request->nivel_referencia[$index] ?? null,
                    'distancia_nivel' => $request->distancia_nivel[$index] ?? null,
                    'direccion_sonda' => $request->direccion_sonda[$index] ?? null,
                    'recubrimiento' => $request->recubrimiento[$index] ?? null,
                ];
            }

            $imagenesGuardadas[] = [
                'ruta' => $rutaPublicaFoto,
                'comentario' => $comentariosFoto[$index] ?? null,
                'es_cuadro_texto' => $esCuadroTexto ? 1 : 0,
                'una_hoja' => !empty($esDisparo[$index]) ? 0 : $distribucionFoto['una_hoja'],
                'pagina' => $distribucionFoto['pagina'],
                'posicion' => $distribucionFoto['posicion'],
                'es_disparo' => !empty($esDisparo[$index]) ? 1 : 0,
                'numero_disparo' => !empty($esDisparo[$index]) ? ($numeroDisparo[$index] ?? null) : null,
                'detalles_junta' => $detallesJunta,
                'datos_junta' => $datosJunta
            ];
        }

        $imagenesGuardadas = ServicioRegistrosFotos::deduplicar($imagenesGuardadas);

        // Convertir el array de fotos a JSON
        $Fotos = json_encode($imagenesGuardadas); 

        // Guardar en la base de datos
        $Fotos_Reportes->idReportes = $idReportes;
        $Fotos_Reportes->Fotos_Reportes = $Fotos;
        $Fotos_Reportes->save();
    }else{
        $imagenesGuardadas = [];
        $Fotos = json_encode($imagenesGuardadas);
        $Fotos = json_encode($imagenesGuardadas); 
        $Fotos_Reportes->idReportes = $idReportes;
        $Fotos_Reportes->Fotos_Reportes = $Fotos;
        $Fotos_Reportes->save();
    }

        $Cliente = $validatedData['Detalles_Generales']['Cliente'];
        $Instalacion = $validatedData['Detalles_Generales']['Instalacion'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'];
        $Material = $validatedData['Detalles_Generales']['Material'];
        $idSolicitud = $validatedData['Detalles_Generales']['idSolicitud'];
        $No_Isometrico = ($validatedData['Detalles_Generales']['No_Isometrico_Plano'] ?? '')
            ?: ($validatedData['Detalles_Generales']['No_Isometrico'] ?? '');

        $datosParaCrearOS_OC = [
            'idPrueba_Aplica' => $idPrueba_Aplica,
            'Cliente' => $Cliente,
            'Lugar' => $Instalacion,
            'Contrato' => $Contrato,
            'Proyecto' => $Proyecto,
            'Material' => $Material,
            'Isometrico_Plano' => $No_Isometrico,
            'ResultadosJuntas' => $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re,
            'idSolicitud' => $idSolicitud,
            'idReportes' => $idReportes,
            
        ];

        $this->OS_OC($datosParaCrearOS_OC);

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
    }

    public function FOR_PIMP_04_02_update(Request $request, $id, ServicioAnalisisImagenImageJ $servicioImagen)
    {
        $Estatus = "ACTUALIZADO";
        // Validar los Detalles_Generales
        $validatedData = $request->validate([
                        /*DETALLES GENERALES */
            'Detalles_Generales' => 'required|array',  // Asegura que es un array
            'Detalles_Generales.Fecha' => 'nullable|date',
            'Detalles_Generales.No_Reporte' => 'nullable|string',
            'Detalles_Generales.Cliente' => 'nullable|string',
            'Detalles_Generales.Contrato' => 'nullable|string',
            'Detalles_Generales.Proyecto' => 'nullable|string',
            'Detalles_Generales.Orden_Trabajo' => 'nullable|string',
            'Detalles_Generales.Folio' => 'nullable|string',
            'Detalles_Generales.Partida' => 'nullable|string',
            'Detalles_Generales.Instalacion' => 'nullable|string',
            'Detalles_Generales.No_Isometrico' => 'nullable|string',
            'Detalles_Generales.Nombre_Pieza' => 'nullable|string',
            'Detalles_Generales.Criterio_Evaluacion' => 'nullable|string',
            'Detalles_Generales.Accesorio' => 'nullable|string',
            'Detalles_Generales.Tuberia' => 'nullable|string',
            'Detalles_Generales.Estructural' => 'nullable|string',
            'Detalles_Generales.No_Isometrico_Plano' => 'nullable|string',
            'Detalles_Generales.Observaciones_Notas' => 'nullable|string',
            'Detalles_Generales.Material' => 'nullable|string',
            'Detalles_Generales.Trazabilidad' => 'nullable|string',
            'Detalles_Generales.Procedimiento' => 'nullable|string',
            'Detalles_Generales.idSolicitud' => 'nullable|string',
            'Detalles_Generales.Num_Soldador' => 'nullable|string',
            'Detalles_Generales.Nombre_Soldador' => 'nullable|string',
            'Detalles_Generales.Reporte_Firmado' => 'nullable|file|mimes:pdf|max:20480',
            // Datos generados por los dos componentes de análisis de imagen.
            'Analisis_Imagen_Token' => 'nullable|uuid',
            'Analisis_Imagen_Usar_Reporte' => 'nullable|boolean',
            'Analisis_Reporte_Comentario_Imagen' => 'nullable|string|max:500',
            'Analisis_Reporte_Descripcion' => 'nullable|string|max:20000',
            'Analisis_Reporte_Layout' => 'nullable|array',
            'Analisis_Reporte_Layout.*.pagina' => 'nullable|integer|min:1|max:999',
            'Analisis_Reporte_Layout.*.posicion' => 'nullable|in:arriba_izquierda,arriba_derecha,abajo_izquierda,abajo_derecha,pagina_completa',
            'Conteo_Granos_JSON' => 'nullable|json|max:100000',
            
            /*DATOS DEL EQUIPO Y OBSERVACIONES*/
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string',
            'Datos_Equipo.NS_EQUIPO' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO' => 'nullable|string',

            'Datos_Equipo.MARCA_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.NS_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO1' => 'nullable|string',

            'Datos_Equipo.Observaciones' => 'nullable|string',
            'Datos_Equipo.ESCALA_DUREZA' => 'nullable|string|max:50',
            'Datos_Equipo.VALORES_DUREZA' => 'nullable|array|max:10',
            'Datos_Equipo.VALORES_DUREZA.*' => 'nullable|string|max:30',
            'Datos_Equipo.PROMEDIO_DUREZA' => 'nullable|string|max:30',
            'Datos_Equipo.DESCRIPCION_MATERIAL' => 'nullable|string|max:255',
            'Datos_Equipo.DUREZA_BRINELL' => 'nullable|string|max:100',
            'Datos_Equipo.RESISTENCIA_TENSION' => 'nullable|string|max:100',
            'Datos_Equipo.RESISTENCIA_CEDENCIA' => 'nullable|string|max:100',
            'Datos_Equipo.TAMANO_GRANO' => 'nullable|string|max:100',
            'Datos_Equipo.NORMA_REFERENCIA' => 'nullable|string|max:255',
            'Datos_Equipo.DUREZA_BRINELL_MAX' => 'nullable|string|max:100',
            'Datos_Equipo.RESISTENCIA_TENSION_MIN' => 'nullable|string|max:100',
            'Datos_Equipo.RESISTENCIA_CEDENCIA_ESPECIFICADA' => 'nullable|string|max:100',
            'Datos_Equipo.RESISTENCIA_TENSION_MAX' => 'nullable|string|max:100',
            'Datos_Equipo.MATERIAL_PANO' => 'nullable|string|max:255',
            'Datos_Equipo.MATERIAL_ABRASIVO' => 'nullable|string|max:255',
            'Datos_Equipo.REACTIVO' => 'nullable|string|max:255',
            'Datos_Equipo.TIEMPO_ATAQUE' => 'nullable|string|max:100',
            'Datos_Equipo.FASES_PRESENTES' => 'nullable|string|max:1000',
            'Datos_Equipo.ESPECIFICACION_MATERIAL' => 'nullable|string|max:1000',
            'Datos_Equipo.QR_TOKEN' => 'nullable|string',
            'Datos_Equipo.QR_PDF' => 'nullable|string',
            'Datos_Equipo.PDF_UNIFICADO' => 'nullable|string',

            'Norma_IM' => 'nullable|array',
            'Norma_IM.idnormas_im' => 'nullable|required_with:Analisis_PDF|integer',
            'Norma_IM.Promedio' => 'nullable|array',
            'Norma_IM.Promedio.*' => 'nullable|string|max:255',
            'Analisis_PDF' => 'nullable|file|mimes:pdf|max:10240',
            'XRF_Columnas' => 'nullable|required_with:Analisis_PDF|array|min:1|max:3',
            'XRF_Columnas.*' => 'required_with:Analisis_PDF|integer|distinct|between:1,20',
            'Patron_Grano' => 'nullable|array',
            'Patron_Grano.id' => 'nullable|required_if:Patron_Grano.activo,1|integer|min:1',
            'Patron_Grano.descripcion' => 'nullable|required_with:Patron_Grano.id|string|max:500',
            'Patron_Grano.activo' => 'nullable|boolean',
            'Patron_Grano.usar_version_catalogo' => 'nullable|boolean',
            'Patron_Grano.layout' => 'nullable|array',
            'Patron_Grano.layout.pagina' => 'nullable|integer|min:1|max:999',
            'Patron_Grano.layout.posicion' => 'nullable|in:arriba_izquierda,arriba_derecha,abajo_izquierda,abajo_derecha,pagina_completa',
            //Validar el campo NumFirmas
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string|max:5000',
            'foto_es_texto' => 'nullable|array',
            'foto_es_texto.*' => 'nullable|boolean',
            'foto_pagina' => 'nullable|array',
            'foto_pagina.*' => 'nullable|integer|min:1',
            'foto_posicion' => 'nullable|array',
            'foto_posicion.*' => 'nullable|in:arriba_izquierda,arriba_derecha,abajo_izquierda,abajo_derecha,pagina_completa',
            'es_disparo' => 'nullable|array',
            'es_disparo.*' => 'nullable|boolean',
            'numero_disparo' => 'nullable|array',
            'numero_disparo.*' => 'nullable|in:1,2,3',
            'numFirmas' => 'required|integer|in:1,2,3,4',

            /*1 FIRMAS */
            'Firmas_Reportes1' => 'required|array',  // Asegura que es un array

            'Firmas_Reportes1.Realizo' => 'nullable|string',
            'Firmas_Reportes1.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.EMPRESA_TECNICO' => 'nullable|string',

            /*2 FIRMAS */
            'Firmas_Reportes2' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes2.Realizo' => 'nullable|string',
            'Firmas_Reportes2.Vobo1' => 'nullable|string',

            'Firmas_Reportes2.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.NOMBRE_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes2.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.PUESTO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes2.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.EMPRESA_ENCARGADO' => 'nullable|string',

            /*3 FIRMAS */
            'Firmas_Reportes3' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes3.Realizo' => 'nullable|string',
            'Firmas_Reportes3.Vobo1' => 'nullable|string',
            'Firmas_Reportes3.Vobo2' => 'nullable|string',

            'Firmas_Reportes3.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.NOMBRE_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.NOMBRE_2DO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes3.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.PUESTO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.PUESTO_2DO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes3.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.EMPRESA_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.EMPRESA_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.NUMERO_FICHA' => 'nullable|string',

            /*4 FIRMAS */
            'Firmas_Reportes4' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes4.Realizo' => 'nullable|string',
            'Firmas_Reportes4.Vobo1' => 'nullable|string',
            'Firmas_Reportes4.Vobo2' => 'nullable|string',
            'Firmas_Reportes4.Vobo3' => 'nullable|string',

            'Firmas_Reportes4.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_3RO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes4.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_3RO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes4.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_3RO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.NUMERO_FICHA' => 'nullable|string',
        ]);

        // Valida el resultado final antes de eliminar o reemplazar archivos existentes.
        $this->validarDistribucionFotosDelRequest($request);
        $detallesRequest = $request->input('Detalles_Generales', []);
        $validatedData['Detalles_Generales']['Codigo_Diseno'] = $validatedData['Detalles_Generales']['Codigo_Diseno']
            ?? $detallesRequest['Codigo_Diseno']
            ?? null;
        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes',$id)->first();
        $Grupo_Juntas_Detalles_Re = Grupo_Juntas_Detalles_Re::where('idReportes',$id)->first();
        $Firmas = Firma_Reporte::firstOrNew(['idReportes' => $id]);
        $Fotos_Reportes = Fotos_Reporte::where('idReportes',$id)->first();

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        // 1. Obtener los detalles actuales que ya están en la base de datos
        $detallesActuales = json_decode($Reporte->Detalles_Generales, true) ?? [];
        $datosEquipoActuales = json_decode($Reporte->Datos_Equipo, true) ?? [];
        // Se conservan las rutas para retirar los PDF anteriores solo después de guardar los nuevos.
        $rutasPdfsXrfAnteriores = [];
        foreach (($detallesActuales['Norma_IM']['Analisis_PDF'] ?? []) as $analisisAnterior) {
            if (is_array($analisisAnterior) && !empty($analisisAnterior['ruta'])) {
                $rutasPdfsXrfAnteriores[] = (string) $analisisAnterior['ruta'];
            }
        }
        if (!empty($detallesActuales['Norma_IM']['Captura_XRF']['ruta'])) {
            $rutasPdfsXrfAnteriores[] = (string) $detallesActuales['Norma_IM']['Captura_XRF']['ruta'];
        }

        if ($request->hasFile('Detalles_Generales.Reporte_Firmado')) {
            
            // 1. ELIMINAR ARCHIVO ANTERIOR (si existe)
            if (!empty($detallesActuales['Reporte_Firmado'])) {
                // Convertimos la ruta de la base de datos (storage/...) de vuelta a la ruta del disco (public/...)
                $archivoViejo = str_replace('storage/', 'public/', $detallesActuales['Reporte_Firmado']);
                
                if (Storage::exists($archivoViejo)) {
                    Storage::delete($archivoViejo);
                }
            }

            // 2. PROCESAR NUEVO ARCHIVO
            $file = $request->file('Detalles_Generales.Reporte_Firmado');
            $rutaBase = "public/Reportes/FOR_PIMP_04_02/{$Contrato}/{$No_Reporte}/Reporte_Firmado";
            $nombreArchivo = 'Reporte_Firmado_' . $No_Reporte . '_' . time() . '.pdf';
            
            $file->storeAs($rutaBase, $nombreArchivo);

            $rutaPublica = str_replace('public/', 'storage/', $rutaBase) . '/' . $nombreArchivo;
            $validatedData['Detalles_Generales']['Reporte_Firmado'] = $rutaPublica;

        } else {
            $validatedData['Detalles_Generales']['Reporte_Firmado'] = $detallesActuales['Reporte_Firmado'] ?? null;
        }

        // Conservar cualquier informacion previa que no venga en el formulario de edicion.
        $validatedData['Detalles_Generales'] = array_merge($detallesActuales, $validatedData['Detalles_Generales']);
        $validatedData['Datos_Equipo'] = array_merge($datosEquipoActuales, $validatedData['Datos_Equipo']);

        // Recalcula valores derivados y actualiza la copia histórica seleccionada por el usuario.
        $this->guardarPromedioDureza($request, $validatedData['Datos_Equipo']);

        $normaIM = $this->construirNormaIM($request, $detallesActuales['Norma_IM'] ?? null);
        if ($normaIM !== null) {
            if ($request->hasFile('Analisis_PDF')) {
                $this->guardarArchivosColumnasXrf(
                    $request->file('Analisis_PDF'),
                    $normaIM,
                    (string) ($validatedData['Detalles_Generales']['Contrato'] ?? ''),
                    (string) ($validatedData['Detalles_Generales']['No_Reporte'] ?? '')
                );
            }
            $validatedData['Detalles_Generales']['Norma_IM'] = $normaIM;
        }

        $rutaPatronAnterior = (string) ($detallesActuales['PATRON_GRANO']['ruta_imagen'] ?? '');
        // Mantiene la copia anterior si el usuario no cambia de patrón; una selección diferente genera otra copia.
        $servicioPatronGrano = app(ServicioPatronGranoReporte::class);
        $patronGrano = $servicioPatronGrano->construirHistorico(
            $request,
            'FOR_PIMP_04_02',
            (string) ($validatedData['Detalles_Generales']['Contrato'] ?? ''),
            (string) ($validatedData['Detalles_Generales']['No_Reporte'] ?? ''),
            is_array($detallesActuales['PATRON_GRANO'] ?? null)
                ? $detallesActuales['PATRON_GRANO']
                : null
        );
        if ($patronGrano === null) {
            unset($validatedData['Detalles_Generales']['PATRON_GRANO']);
        } else {
            $validatedData['Detalles_Generales']['PATRON_GRANO'] = $patronGrano;
        }

        // Un token nuevo reemplaza el análisis anterior; si no llega, array_merge conserva el existente.
        if (!empty($validatedData['Analisis_Imagen_Token'])) {
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN'] = $servicioImagen->obtenerPorToken(
                $validatedData['Analisis_Imagen_Token'],
                (int) Auth::id()
            );
        }
        // En Edit también se permite activar o retirar del PDF el análisis previamente guardado.
        if (is_array($validatedData['Detalles_Generales']['ANALISIS_IMAGEN'] ?? null)) {
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['usar_en_reporte'] =
                !empty($validatedData['Analisis_Imagen_Usar_Reporte']);
            // Edit conserva o corrige el pie de fotografía sin volver a ejecutar Fiji.
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['comentario_imagen_reporte'] =
                trim((string) ($validatedData['Analisis_Reporte_Comentario_Imagen'] ?? ''));
            // Edit permite corregir la redacción sin volver a ejecutar Fiji ni subir la micrografía.
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['descripcion_reporte'] =
                trim((string) ($validatedData['Analisis_Reporte_Descripcion'] ?? ''));
            // Edit actualiza solamente la distribución seleccionada, sin volver a ejecutar Fiji.
            $validatedData['Detalles_Generales']['ANALISIS_IMAGEN']['layout_reporte'] =
                app(ServicioMetalografiaReporte::class)->normalizarLayoutAnalisis(
                    $validatedData['Analisis_Reporte_Layout'] ?? []
                );
        }
        // Recalcula y sustituye el conteo solamente cuando el componente envía JSON válido.
        $conteoGranos = $this->normalizarConteoGranos($validatedData['Conteo_Granos_JSON'] ?? null);
        if ($conteoGranos !== null) {
            $validatedData['Detalles_Generales']['CONTEO_GRANOS'] = $conteoGranos;
        }
        
        $validatedData['Datos_Equipo']['ID_EQUIPO'] = $validatedData['Datos_Equipo']['ID_EQUIPO'] ?? ($datosEquipoActuales['ID_EQUIPO'] ?? null);
        $validatedData['Datos_Equipo']['ID_EQUIPO1'] = $validatedData['Datos_Equipo']['ID_EQUIPO1'] ?? ($datosEquipoActuales['ID_EQUIPO1'] ?? null);
        $validatedData['Datos_Equipo']['ID_SONDA'] = $validatedData['Datos_Equipo']['ID_SONDA'] ?? ($datosEquipoActuales['ID_SONDA'] ?? null);
        $validatedData['Datos_Equipo']['ID_BLOCK'] = $validatedData['Datos_Equipo']['ID_BLOCK'] ?? ($datosEquipoActuales['ID_BLOCK'] ?? null);
        $validatedData['Datos_Equipo']['QR_TOKEN'] = $validatedData['Datos_Equipo']['QR_TOKEN'] ?? ($datosEquipoActuales['QR_TOKEN'] ?? (string) Str::uuid());

        $datosParaCrearQR = [
            'Contrato' => $validatedData['Detalles_Generales']['Contrato'] ?? null,
            'No_Reporte' => $validatedData['Detalles_Generales']['No_Reporte'] ?? null,
            'qr_token' => $validatedData['Datos_Equipo']['QR_TOKEN'],
            'idEquipo' => $validatedData['Datos_Equipo']['ID_EQUIPO'] ?? null,
            'idEquipo1' => $validatedData['Datos_Equipo']['ID_EQUIPO1'] ?? null,
            'idSonda' => $validatedData['Datos_Equipo']['ID_SONDA'] ?? null,
            'idBlock' => $validatedData['Datos_Equipo']['ID_BLOCK'] ?? null,
        ];

        $resultadoQR = $this->Datos_QR($datosParaCrearQR);
        $validatedData['Datos_Equipo']['QR_PDF'] = $resultadoQR['qr'] ?? ($datosEquipoActuales['QR_PDF'] ?? null);
        $validatedData['Datos_Equipo']['PDF_UNIFICADO'] = $resultadoQR['pdf'] ?? ($datosEquipoActuales['PDF_UNIFICADO'] ?? null);

        // Actualiza los detalles generales como JSON en la base de datos
        $Reporte->update([
            'Detalles_Generales' => json_encode($validatedData['Detalles_Generales']),
            'Datos_Equipo' => json_encode($validatedData['Datos_Equipo']) 
        ]);

        // La baja de la copia sustituida ocurre después de confirmar la actualización del reporte.
        $rutaPatronNueva = (string) ($validatedData['Detalles_Generales']['PATRON_GRANO']['ruta_imagen'] ?? '');
        $servicioPatronGrano->eliminarCopiaSustituida($rutaPatronAnterior, $rutaPatronNueva);

        $titulos_json = $request->input('titulos_data', '[]');
        //dd($titulos_json);
        $titulos = json_decode($titulos_json, true); // array asociativo
        $datosAgrupados = [];
        
        // 1. Procesar filas SIN título (si existen)
        $sinTituloKey = 'sin_titulo';
        $filasSinTitulo = $request->input("no.$sinTituloKey", []);
        //$longitudesSin = $request->input("Long_Inspecc.$sinTituloKey", []);
        $numFilasSin = count($filasSinTitulo);//agregar

        // 🔹 cuántas filas debe tener cada bloque
        $maxFilasPorBloque = 21; //Agregar 1 + que en create y edit para que la longitud entre en el mismo bloque

        $bloques = []; //agregar
        $bloqueActual = [];//agregar
        $contador = 0;//agregar
        /*//agregar
        |--------------------------------------------------------------------------
        | FUNCIONES AUXILIARES
        |--------------------------------------------------------------------------
        */
        $cerrarBloque = function () use (&$bloques, &$bloqueActual, &$contador) {
            if (!empty($bloqueActual)) {
                $bloques[] = $bloqueActual;
                $bloqueActual = [];
                $contador = 0;
            }
        };

        $agregarElemento = function ($elemento) use (&$bloques, &$bloqueActual, &$contador, $maxFilasPorBloque) {
            if ($contador >= $maxFilasPorBloque) {
                $bloques[] = $bloqueActual;
                $bloqueActual = [];
                $contador = 0;
            }

            $bloqueActual[] = $elemento;
            $contador++;
        };

        /*
        |--------------------------------------------------------------------------
        | 1. BLOQUE SIN TITULO
        |--------------------------------------------------------------------------
        */
                $longitudesSin = $request->input("Long_Inspecc.$sinTituloKey", []);
                // Debe coincidir con verificarYAgregarLongitud() del JS: inserta una longitud cada 15 filas
                $filasPorLongitud = 20;
                for ($i = 0; $i < $numFilasSin; $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $sinTituloKey,
                    'data' => [
                        'no' => $request->input("no.$sinTituloKey.$i"),
                        'junta' => $request->input("junta.$sinTituloKey.$i"),
                        'lado' => $request->input("lado.$sinTituloKey.$i"),
                        'no_ind' => $request->input("no_ind.$sinTituloKey.$i"),
                        'tipo_ind' => $request->input("tipo_ind.$sinTituloKey.$i"),
                        'long' => $request->input("long.$sinTituloKey.$i"),
                        'prof' => $request->input("prof.$sinTituloKey.$i"),
                        'NR' => $request->input("NR.$sinTituloKey.$i"),
                        'dnr' => $request->input("dnr.$sinTituloKey.$i"),
                        'evaluacion' => $request->input("evaluacion.$sinTituloKey.$i"),
                        'archivo' => $request->input("archivo.$sinTituloKey.$i"),
                        'long_ins' => $request->input("long_ins.$sinTituloKey.$i"),
                    ]
                    ]);

                    // Cada 15 filas, intercalar la longitud correspondiente (replica el orden del DOM)
                    if (($i + 1) % $filasPorLongitud === 0) {
                        $idxLong = intdiv($i, $filasPorLongitud);
                        if (isset($longitudesSin[$idxLong])) {
                            $agregarElemento([
                                'tipo' => 'longitud',
                                'grupo' => $sinTituloKey,
                                'valor' => $longitudesSin[$idxLong]
                            ]);
                            $cerrarBloque();
                        }
                    }
                }

                // Longitudes restantes (si el usuario agregó longitudes manuales extra o el último bloque tiene <15 filas)
                $longsUsadas = intdiv($numFilasSin, $filasPorLongitud);
                $totalLongs = count($longitudesSin);
                for ($j = $longsUsadas; $j < $totalLongs; $j++) {
                    $agregarElemento([
                        'tipo' => 'longitud',
                        'grupo' => $sinTituloKey,
                        'valor' => $longitudesSin[$j]
                    ]);
                    $cerrarBloque();
                }

        /*
        |--------------------------------------------------------------------------
        | 2. TITULOS + FILAS + LONGITUDES
        |--------------------------------------------------------------------------
        */

        foreach ($titulos as $tituloObj) {
            $tituloKey = $tituloObj['id'];   // ej. "titulo_1"
            $tituloText = $tituloObj['text']; // texto real

            // agregar título
            $agregarElemento([
                'tipo' => 'titulo',
                'grupo' => $tituloKey,
                'texto' => $tituloText
            ]);

            $filas = $request->input("no.$tituloKey", []);
            $numFilas = count($filas);
        
            //$resultados = [];
        
            for ($i = 0; $i < $numFilas; $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $tituloKey,
                    'data' => [
                    'no' => $request->input("no.$tituloKey.$i"),
                    'junta' => $request->input("junta.$tituloKey.$i"),
                    'lado' => $request->input("lado.$tituloKey.$i"),
                    'no_ind' => $request->input("no_ind.$tituloKey.$i"),
                    'tipo_ind' => $request->input("tipo_ind.$tituloKey.$i"),
                    'long' => $request->input("long.$tituloKey.$i"),
                    'prof' => $request->input("prof.$tituloKey.$i"),
                    'NR' => $request->input("NR.$tituloKey.$i"),
                    'dnr' => $request->input("dnr.$tituloKey.$i"),
                    'evaluacion' => $request->input("evaluacion.$tituloKey.$i"),
                    'archivo' => $request->input("archivo.$tituloKey.$i"),
                    'long_ins' => $request->input("long_ins.$tituloKey.$i"),
                    ]
                ]);
            }

            // Obtener longitud inspeccionada asociada a este título (si existe)
            $longitudes = $request->input("Long_Inspecc.$tituloKey", []); //Agregar

                foreach ($longitudes as $long) {
                    $agregarElemento([
                        'tipo' => 'longitud',
                        'grupo' => $tituloKey,
                        'valor' => $long
                    ]);

                    // cerrar bloque al encontrar longitud
                    $cerrarBloque();
                }
        }
        /*
        |--------------------------------------------------------------------------
        | 3. CERRAR SI QUEDAN ELEMENTOS
        |--------------------------------------------------------------------------
        */
        $cerrarBloque();
        /*
        |--------------------------------------------------------------------------
        | 4. GUARDAR
        |--------------------------------------------------------------------------
        */
        // Actualizar o crear el campo en la base de datos
        if ($Grupo_Juntas_Detalles_Re) {
            $Grupo_Juntas_Detalles_Re->update([
                'Juntas_Grupo_Re' => json_encode($bloques, JSON_UNESCAPED_UNICODE)
            ]);
        } else {
            $Grupo_Juntas_Detalles_Re = new Grupo_Juntas_Detalles_Re();
            $Grupo_Juntas_Detalles_Re->idReportes = $id;
            $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = json_encode($bloques, JSON_UNESCAPED_UNICODE);
            $Grupo_Juntas_Detalles_Re->save();
        }

        /*Firmas */
        // Guardar las firmas
        $numFirmas = $request->input('numFirmas'); // Obtener el número de firmas seleccionadas
        
        if ($numFirmas == 1) {
            $validatedData['Firmas_Reportes1']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas1 = json_encode($validatedData['Firmas_Reportes1']);
            $Firmas->Firmas = $Firmas1;
            $Firmas->save();
        }
        else if ($numFirmas == 2) {
            $validatedData['Firmas_Reportes2']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas2 = json_encode($validatedData['Firmas_Reportes2']);
            $Firmas->Firmas = $Firmas2;
            $Firmas->save();
        }
        else if ($numFirmas == 3) {
            $validatedData['Firmas_Reportes3']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas3 = json_encode($validatedData['Firmas_Reportes3']);
            $Firmas->Firmas = $Firmas3;
            $Firmas->save();
        }
        else{
            $validatedData['Firmas_Reportes4']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas4 = json_encode($validatedData['Firmas_Reportes4']);
            $Firmas->Firmas = $Firmas4;
            $Firmas->save();
        } 

        /* Fotos y Comentarios */
        // Obtener los valores necesarios para la ruta personalizada
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'] ?? ''; // Asegurar que Contrato está definido

        // Ruta base para guardar las imágenes
        $rutaCarpeta = "public/Reportes/FOR_PIMP_04_02/{$Contrato}/{$No_Reporte}/Fotos";

        // Reúne estado existente, altas, bajas y opciones de presentación en los mismos índices.
        $existingImages = $request->input('existing_images', []);
        $comments = $request->input('comments', []);
        $imagesBase64 = $request->input('images_base64', []);
        $fotoEsTexto = $request->input('foto_es_texto', []);
        $deletedImages = $request->input('deleted_images', []);
        $esDisparo = $request->input('es_disparo', []);
        $numeroDisparo = $request->input('numero_disparo', []);
        $fotoPaginas = $request->input('foto_pagina', []);
        $fotoPosiciones = $request->input('foto_posicion', []);
        //Log::info('Imágenes eliminadas recibidas:', ['deletedImages' => $deletedImages]);
        $detallesJuntaCheck = $request->input('detalles_junta_check', []);

        $junta = $request->input('junta', []);
        $noIndicacion = $request->input('no_indicacion', []);
        $tipoIndicacion = $request->input('tipo_indicacion', []);
        $longitud = $request->input('longitud', []);
        $profundidad = $request->input('profundidad', []);
        $nivelReferencia = $request->input('nivel_referencia', []);
        $distanciaNivel = $request->input('distancia_nivel', []);
        $direccionSonda = $request->input('direccion_sonda', []);
        $recubrimiento = $request->input('recubrimiento', []);
        

        // Centraliza la forma JSON de los detalles para fotos existentes y nuevas.
        $getDetallesJunta = function ($index) use (
            $detallesJuntaCheck,
            $junta,
            $noIndicacion,
            $tipoIndicacion,
            $longitud,
            $profundidad,
            $nivelReferencia,
            $distanciaNivel,
            $direccionSonda,
            $recubrimiento,
        ) {
            $activo = $detallesJuntaCheck[$index] ?? 0;

            if (!$activo) {
                return [
                    'detalles_junta' => 0,
                    'datos_junta' => null
                ];
            }

            return [
                'detalles_junta' => 1,
                'datos_junta' => [
                    'junta' => $junta[$index] ?? null,
                    'no_indicacion' => $noIndicacion[$index] ?? null,
                    'tipo_indicacion' => $tipoIndicacion[$index] ?? null,
                    'longitud' => $longitud[$index] ?? null,
                    'profundidad' => $profundidad[$index] ?? null,
                    'nivel_referencia' => $nivelReferencia[$index] ?? null,
                    'distancia_nivel' => $distanciaNivel[$index] ?? null,
                    'direccion_sonda' => $direccionSonda[$index] ?? null,
                    'recubrimiento' => $recubrimiento[$index] ?? null,
                ]
            ];
        };

        // Evita que cada rama de actualización interprete de forma distinta página y posición.
        $getDistribucionFoto = function ($index) use ($fotoPaginas, $fotoPosiciones) {
            return $this->normalizeFotoLayout(
                $fotoPaginas[$index] ?? null,
                $fotoPosiciones[$index] ?? null,
                $index
            );
        };
        // Construye el registro completo tanto para una imagen como para un cuadro de texto sin archivo.
        $crearRegistroFoto = function ($index, $ruta) use (
            $comments,
            $esDisparo,
            $numeroDisparo,
            $fotoEsTexto,
            $getDetallesJunta,
            $getDistribucionFoto,
        ) {
            $detalles = $getDetallesJunta($index);
            $distribucionFoto = $getDistribucionFoto($index);
            $esCuadroTexto = !empty($fotoEsTexto[$index]);

            return [
                'ruta' => $ruta ?: null,
                'comentario' => $comments[$index] ?? '',
                'es_cuadro_texto' => $esCuadroTexto ? 1 : 0,
                'una_hoja' => !empty($esDisparo[$index]) ? 0 : $distribucionFoto['una_hoja'],
                'pagina' => $distribucionFoto['pagina'],
                'posicion' => $distribucionFoto['posicion'],
                'es_disparo' => !empty($esDisparo[$index]) ? 1 : 0,
                'numero_disparo' => !empty($esDisparo[$index]) ? ($numeroDisparo[$index] ?? null) : null,
                'detalles_junta' => $detalles['detalles_junta'],
                'datos_junta' => $detalles['datos_junta'],
            ];
        };
        // **1️⃣ Eliminar imágenes marcadas para borrar**
        foreach ($deletedImages as $index) {
            $rutaExistente = trim((string) ($existingImages[$index] ?? ''));

            // Solo las fotografías tienen archivo físico; un cuadro de texto utiliza una ruta vacía.
            if ($rutaExistente !== '') {
                $rutaImagen = str_replace('storage/', 'public/', $rutaExistente);
                if (Storage::exists($rutaImagen)) {
                    Storage::delete($rutaImagen);
                }
            }

            // La baja se aplica por índice incluso si no existe ruta, corrigiendo cuadros de texto eliminados.
            unset(
                $existingImages[$index],
                $fotoEsTexto[$index],
                $imagesBase64[$index],
                $comments[$index],
                $fotoPaginas[$index],
                $fotoPosiciones[$index],
                $esDisparo[$index],
                $numeroDisparo[$index]
            );
        }

        // **Reiniciar el array antes de procesar imágenes**
        $imagenesGuardadas = [];

        // **Evitar duplicados en las rutas ya guardadas**
        $rutasGuardadas = [];

        // **2️⃣ Procesar imágenes existentes**
        foreach ($existingImages as $index => $ruta) {
            if (!empty($fotoEsTexto[$index])) {
                $imagenesGuardadas[] = $crearRegistroFoto($index, $ruta);
                if (!empty($ruta)) {
                    $rutasGuardadas[] = $ruta;
                }
                continue;
            }

            if ($request->hasFile("replace_images.$index") && empty($imagesBase64[$index])) {
                // **Reemplazo de imagen existente**
                $newImage = $request->file("replace_images.$index");

                // Eliminar imagen anterior si existe
                $rutaImagenPublic = str_replace('storage/', 'public/', $ruta);
                if (Storage::exists($rutaImagenPublic)) {
                    Storage::delete($rutaImagenPublic);
                }

                // Guardar la nueva imagen
                $imageName = 'imagen_' . time() . '_' . $index . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs($rutaCarpeta, $imageName);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                // Verificar si ya existe en el array
                if (!in_array($rutaNueva, $rutasGuardadas)) {
                $detalles = $getDetallesJunta($index);
                $distribucionFoto = $getDistribucionFoto($index);

                $imagenesGuardadas[] = [
                    'ruta' => $rutaNueva,
                    'comentario' => $comments[$index] ?? '',
                    'una_hoja' => !empty($esDisparo[$index]) ? 0 : $distribucionFoto['una_hoja'],
                    'pagina' => $distribucionFoto['pagina'],
                    'posicion' => $distribucionFoto['posicion'],
                    'es_disparo' => !empty($esDisparo[$index]) ? 1 : 0,
                    'numero_disparo' => !empty($esDisparo[$index]) ? ($numeroDisparo[$index] ?? null) : null,
                    'detalles_junta' => $detalles['detalles_junta'],
                    'datos_junta' => $detalles['datos_junta'],
                ];
                    $rutasGuardadas[] = $rutaNueva; // Guardar ruta para evitar duplicados
                }
            } elseif (!empty($imagesBase64[$index])) {
                // **Procesar imágenes en Base64**
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imagesBase64[$index]));
                $imageName = 'imagen_' . time() . '_' . $index . '.png';
                $path = "{$rutaCarpeta}/{$imageName}";

                // Guardar la imagen
                Storage::put($path, $image);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                // Verificar si ya existe en el array
                if (!in_array($rutaNueva, $rutasGuardadas)) {
                $detalles = $getDetallesJunta($index);
                $distribucionFoto = $getDistribucionFoto($index);

                $imagenesGuardadas[] = [
                    'ruta' => $rutaNueva,
                    'comentario' => $comments[$index] ?? '',
                    'una_hoja' => !empty($esDisparo[$index]) ? 0 : $distribucionFoto['una_hoja'],
                    'pagina' => $distribucionFoto['pagina'],
                    'posicion' => $distribucionFoto['posicion'],
                    'es_disparo' => !empty($esDisparo[$index]) ? 1 : 0,
                    'numero_disparo' => !empty($esDisparo[$index]) ? ($numeroDisparo[$index] ?? null) : null,
                    'detalles_junta' => $detalles['detalles_junta'],
                    'datos_junta' => $detalles['datos_junta'],
                ];
                    $rutasGuardadas[] = $rutaNueva;
                }
            } else {
                // **Mantener la imagen existente**
                if (!empty($ruta) && !in_array($ruta, $rutasGuardadas)) {
                $detalles = $getDetallesJunta($index);
                $distribucionFoto = $getDistribucionFoto($index);

                $imagenesGuardadas[] = [
                    'ruta' => $ruta,
                    'comentario' => $comments[$index] ?? '',
                    'una_hoja' => !empty($esDisparo[$index]) ? 0 : $distribucionFoto['una_hoja'],
                    'pagina' => $distribucionFoto['pagina'],
                    'posicion' => $distribucionFoto['posicion'],
                    'es_disparo' => !empty($esDisparo[$index]) ? 1 : 0,
                    'numero_disparo' => !empty($esDisparo[$index]) ? ($numeroDisparo[$index] ?? null) : null,
                    'detalles_junta' => $detalles['detalles_junta'],
                    'datos_junta' => $detalles['datos_junta'],
                ];
                    $rutasGuardadas[] = $ruta;
                }
            }
        }

        // **3️⃣ Procesar nuevas imágenes Base64**
        foreach ($imagesBase64 as $index => $base64Image) {
            if (isset($existingImages[$index])) {
                continue; // ⛔ ya fue procesada arriba
            }

            if (!empty($fotoEsTexto[$index])) {
                $imagenesGuardadas[] = $crearRegistroFoto($index, null);
                continue;
            }

            if (!empty($base64Image)) {
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
                $imageName = 'imagen_' . time() . '_' . $index . '.png';
                $path = "{$rutaCarpeta}/{$imageName}";

                // Guardar la imagen en el almacenamiento
                Storage::put($path, $image);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                if (!in_array($rutaNueva, $rutasGuardadas)) {
                $detalles = $getDetallesJunta($index);
                $distribucionFoto = $getDistribucionFoto($index);

                $imagenesGuardadas[] = [
                    'ruta' => $rutaNueva,
                    'comentario' => $comments[$index] ?? '',
                    'una_hoja' => !empty($esDisparo[$index]) ? 0 : $distribucionFoto['una_hoja'],
                    'pagina' => $distribucionFoto['pagina'],
                    'posicion' => $distribucionFoto['posicion'],
                    'es_disparo' => !empty($esDisparo[$index]) ? 1 : 0,
                    'numero_disparo' => !empty($esDisparo[$index]) ? ($numeroDisparo[$index] ?? null) : null,
                    'detalles_junta' => $detalles['detalles_junta'],
                    'datos_junta' => $detalles['datos_junta'],
                ];
                    $rutasGuardadas[] = $rutaNueva;
                }
            }
        }

        $imagenesGuardadas = ServicioRegistrosFotos::deduplicar($imagenesGuardadas);

        // **4️⃣ Guardar las imágenes actualizadas en la BD**
        if ($Fotos_Reportes) {
            $Fotos_Reportes->update([
                'Fotos_Reportes' => json_encode(array_values($imagenesGuardadas)), // Se usa array reindexado
            ]);
        } else {
            $Fotos_Reportes = new Fotos_Reporte();
            $Fotos_Reportes->idReportes = $id;
            $Fotos_Reportes->Fotos_Reportes = json_encode(array_values($imagenesGuardadas));
            $Fotos_Reportes->save();
        }

        //Log::info('Imágenes finales guardadas en BD:', ['imagenesGuardadas' => $imagenesGuardadas]);

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'];

        if ($request->hasFile('Analisis_PDF')) {
            foreach (array_unique($rutasPdfsXrfAnteriores) as $rutaAnterior) {
                $rutaDisco = str_replace('storage/', 'public/', $rutaAnterior);
                if (Storage::exists($rutaDisco)) {
                    Storage::delete($rutaDisco);
                }
            }
        }

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
    }

    public function FOR_PIMP_04_02($id)
    {

        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes', $id)->first();
        $Grupo_Juntas_Detalles_Re_Model = Grupo_Juntas_Detalles_Re::where('idReportes', $id)->first();
        $Firmas_Reportes = Firma_Reporte::where('idReportes', $id)->first();
        $Fotos_Reportes = Fotos_Reporte::where('idReportes', $id)->first();

        // Decodificar el campo Detalles_Generales para obtener el nombre del proyecto
        $Detalles_Generales = json_decode($Reporte->Detalles_Generales, true) ?? [];
        // Decodificar el campo Datos_Equipo para obtener el nombre del proyecto
        $Datos_Equipo = json_decode($Reporte->Datos_Equipo, true) ?? [];
        $NormaIM = $Detalles_Generales['Norma_IM'] ?? [];
        $NormaIM = is_array($NormaIM) ? $NormaIM : [];
        // Decodificar el campo Grupo_Juntas_Detalles_Re para obtener el nombre del proyecto
        $Grupo_Juntas_Detalles_Re = $Grupo_Juntas_Detalles_Re_Model
            ? json_decode($Grupo_Juntas_Detalles_Re_Model->Juntas_Grupo_Re, true)
            : [];

        if (!is_array($Grupo_Juntas_Detalles_Re)) {
            $Grupo_Juntas_Detalles_Re = [];
        }

        $totalTitulos = 0;
        $totalFilas = 0;

        foreach ($Grupo_Juntas_Detalles_Re as $bloque) {
            if (!is_array($bloque)) continue;

            foreach ($bloque as $item) {
                if (($item['tipo'] ?? '') === 'titulo') {
                    $totalTitulos++;
                }

                if (($item['tipo'] ?? '') === 'fila') {
                    $totalFilas++;
                }
            }
        }

        $totalTitulosYFilas = $totalTitulos + $totalFilas;

        $Firmas_Reportes = $Firmas_Reportes
            ? (json_decode($Firmas_Reportes->Firmas, true) ?? [])
            : [];
        $numFirmas = (int) ($Firmas_Reportes['numFirmas'] ?? 1);

        $Logo = public_path('images/Logo_AICO_R.jpg');
        $qrPdf = !empty($Datos_Equipo['QR_PDF']) ? public_path(str_replace('storage/', 'storage/', $Datos_Equipo['QR_PDF'])) : null;
        $CapturaXrf = null;
        if (!empty($NormaIM['Captura_XRF']['ruta'])) {
            $rutaCaptura = storage_path('app/public/' . str_replace('storage/', '', $NormaIM['Captura_XRF']['ruta']));
            $CapturaXrf = File::exists($rutaCaptura) ? $rutaCaptura : null;
        }
        $Fotos = [];
        $Disparos = [];
        $totalFotos = 0;
        // Obtener las fotos con su comentario
        if ($Fotos_Reportes) {
            $fotos = ServicioRegistrosFotos::deduplicar(
                json_decode($Fotos_Reportes->Fotos_Reportes, true) ?? []
            );

            foreach ($fotos as $indiceFoto => $foto) {
                $esCuadroTexto = !empty($foto['es_cuadro_texto']);
                $rutaFoto = null;
                if (!$esCuadroTexto) {
                    $rutaFoto = storage_path('app/public/' . str_replace('storage/', '', $foto['ruta'] ?? ''));

                    if (!File::exists($rutaFoto)) {
                        continue;
                    }
                }

                $detallesActivo = $foto['detalles_junta'] ?? 0;
                $datosJunta = $foto['datos_junta'] ?? null;
                $distribucionFoto = $this->normalizeFotoLayout(
                    $foto['pagina'] ?? null,
                    $foto['posicion'] ?? (!empty($foto['una_hoja']) ? 'pagina_completa' : null),
                    $indiceFoto
                );

                $Fotos[] = [
                    'path' => $rutaFoto,
                    'comment' => $foto['comentario'] ?? '',
                    'es_cuadro_texto' => $esCuadroTexto ? 1 : 0,
                    'una_hoja' => $distribucionFoto['una_hoja'],
                    'pagina' => $distribucionFoto['pagina'],
                    'posicion' => $distribucionFoto['posicion'],

                    // 🔥 NUEVO
                    'detalles_junta' => $detallesActivo,
                    'datos_junta' => $datosJunta,
                ];
            }
            $totalFotos = count($Fotos);
        }

        // Los dos espacios automáticos se agregan al final para que tengan prioridad en la página 1.
        $this->agregarAnalisisSeleccionadoAlPdf($Fotos, $Detalles_Generales);
        // La copia histórica del patrón ocupa el tercer espacio y reemplaza cualquier asignación manual antigua.
        app(ServicioPatronGranoReporte::class)->agregarAlPdf($Fotos, $Detalles_Generales);
        $totalFotos = count($Fotos);

        $data = [
            'title' => 'Reporte_FOR-PIMP-04-02.PDF',
            'Logo' => $Logo,
            //Detalles_Generales
            'Detalles_Generales' => $Detalles_Generales,
            //Datos_Equipo
            'Datos_Equipo' => $Datos_Equipo,
            'NormaIM' => $NormaIM,
            'CapturaXrf' => $CapturaXrf,
            'QR_PDF' => $qrPdf,
            //Grupo_Juntas_Detalles_Re
            'Grupo_Juntas_Detalles_Re' => $Grupo_Juntas_Detalles_Re,
            //Total de Juntas
            /*'totalTitulos' => $totalTitulos,
            'totalFilas' => $totalFilas,*/
            'totalTitulosYFilas' => $totalTitulosYFilas,
            //Fotos_Reportes
            'Fotos' => $Fotos,
            'Disparos' => $Disparos,
            //Total de Fotos
            'totalFotos' => $totalFotos,
            //Numero de Firmas
            'numFirmas' => $numFirmas,
            //Firmas
            'Firmas_Reportes' => $Firmas_Reportes,
        ];

        // Generar el PDF principal en orientación horizontal
        $pdf1 = PDF::loadView('Reportes.ReportesPDFIM.Reporte_FOR_PIMP_04_02_PDF', $data)->setPaper('letter', 'portrait');
        $pdf2Content = null;
        $pageCount2 = 0;

        if (!empty($Fotos)) {
            $pdf2 = PDF::loadView('Reportes.ReportesFotosPDFIM.Reporte_FOTOS_FOR_PIMP_04_02_PDF', $data)->setPaper('letter', 'portrait');
            $pdf2Content = $pdf2->output();
        }

        // Generar el PDF adicional en orientación vertical

        // Combinar los PDFs
        $pdf1Content = $pdf1->output();

       // Crear objetos FPDI independientes para contar páginas
        $tempPdf1 = new Fpdi();
        $pageCount1 = $tempPdf1->setSourceFile(StreamReader::createByString($pdf1Content));

        if ($pdf2Content) {
            $tempPdf2 = new Fpdi();
            $pageCount2 = $tempPdf2->setSourceFile(StreamReader::createByString($pdf2Content));
        }


        // Ahora sí combinamos
        $combinedPdf = new Fpdi();
        $totalPageCount = $pageCount1 + $pageCount2;

        // Añadir páginas del primer PDF
        $combinedPdf->setSourceFile(StreamReader::createByString($pdf1Content));
        for ($i = 1; $i <= $pageCount1; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $combinedPdf->AddPage('P');
            $combinedPdf->useTemplate($tplId, 0, 0, 210, 297);
            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY(151.5, 25);
            $combinedPdf->Cell(24, 3.5, "$i DE $totalPageCount", 0, 0, 'C');
        }

        // Añadir páginas del segundo PDF

        if ($pdf2Content) {
            $combinedPdf->setSourceFile(StreamReader::createByString($pdf2Content));
            for ($i = 1; $i <= $pageCount2; $i++) {
                $tplId = $combinedPdf->importPage($i);
                $combinedPdf->AddPage('P');
                $combinedPdf->useTemplate($tplId, 0, 0, 210, 297);
                $combinedPdf->SetFont('Arial', 'B', 8);
                $paginaActual = $i + $pageCount1;
                $combinedPdf->SetXY(151.5, 25);
                $combinedPdf->Cell(24, 3.5, "$paginaActual DE $totalPageCount", 0, 0, 'C');
            }
        }

        return response($combinedPdf->Output('Reporte_FOR_PIMP_04_02.PDF', 'S'), 200)
            ->header('Content-Type', 'application/pdf');
    }

    /**
     * Display the specified resource.
     */
    public function show(reporte $reporte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(reporte $reporte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, reporte $reporte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(reporte $reporte)
    {
        //
    }
}
