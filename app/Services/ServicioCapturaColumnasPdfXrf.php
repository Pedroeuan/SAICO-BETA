<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;
use Smalot\PdfParser\Parser;
use Symfony\Component\Process\Process;

class ServicioCapturaColumnasPdfXrf
{
    /** Inyecta los servicios necesarios para leer y analizar las tablas del PDF. */
    public function __construct(
        private readonly Parser $parser,
        private readonly ServicioAnalisisColumnasPdfXrf $analysisService
    ) {}

    /** Genera una imagen con las columnas seleccionadas de la tabla XRF. */
    public function generate(
        UploadedFile $file,
        array $selectedColumns,
        bool $captureCompleteTable = false
    ): array
    {
        if (!function_exists('imagecreatefrompng')) throw new RuntimeException('La extensión GD de PHP no está disponible.');
        $columns = array_values(array_unique(array_map('intval', $selectedColumns)));
        if (count($columns) < 1 || count($columns) > 3 || array_filter($columns, static fn (int $column): bool => $column < 1 || $column > 20)) {
            throw new RuntimeException('Deben seleccionarse entre una y tres columnas válidas entre 1 y 20.');
        }

        $temporaryDirectory = storage_path('app/temp_xrf_columns/' . Str::uuid());
        File::makeDirectory($temporaryDirectory, 0755, true);
        $renderedPage = $temporaryDirectory . DIRECTORY_SEPARATOR . 'page.png';
        try {
            if ($captureCompleteTable) {
                return $this->generateCompleteTableCapture(
                    $file->getRealPath(),
                    $columns,
                    $temporaryDirectory
                );
            }
            $this->renderPage($file->getRealPath(), $renderedPage, 1);
            $source = @imagecreatefrompng($renderedPage);
            if ($source === false) throw new RuntimeException('No fue posible abrir la página seleccionada del PDF.');
            try {
                $pageWidth = imagesx($source);
                $pageHeight = imagesy($source);
                $top = (int) round($pageHeight * 0.377);
                $height = (int) round($pageHeight * 0.421);
                $labelsX = (int) round($pageWidth * 0.046);
                $labelsWidth = (int) round($pageWidth * 0.112);
                $columnsX = (float) ($pageWidth * 0.158);
                $columnWidth = (int) round($pageWidth * 0.100);
                $canvas = imagecreatetruecolor($labelsWidth + ($columnWidth * count($columns)), $height);
                if ($canvas === false) throw new RuntimeException('No fue posible crear la imagen del disparo.');
                try {
                    $white = imagecolorallocate($canvas, 255, 255, 255);
                    imagefill($canvas, 0, 0, $white);
                    imagecopy($canvas, $source, 0, 0, $labelsX, $top, $labelsWidth, $height);
                    foreach ($columns as $position => $column) {
                        $sourceX = (int) round($columnsX + (($column - 1) * $columnWidth));
                        imagecopy($canvas, $source, $labelsWidth + ($position * $columnWidth), 0, $sourceX, $top, $columnWidth, $height);
                    }

                    // Al seleccionar una columna intermedia no se copia el borde derecho
                    // original de la tabla. Limpia los remates horizontales y cierra el
                    // recorte con una sola linea continua.
                    $canvasWidth = imagesx($canvas);
                    $black = imagecolorallocate($canvas, 0, 0, 0);
                    imagefilledrectangle($canvas, $canvasWidth - 2, 0, $canvasWidth - 1, $height - 1, $white);
                    imageline($canvas, $canvasWidth - 1, 0, $canvasWidth - 1, $height - 1, $black);

                    ob_start();
                    imagepng($canvas, null, 6);
                    $binary = ob_get_clean();
                    if (!is_string($binary) || $binary === '') throw new RuntimeException('No fue posible codificar la imagen del disparo.');
                    return ['data_url' => 'data:image/png;base64,' . base64_encode($binary), 'ancho' => imagesx($canvas), 'alto' => imagesy($canvas), 'columnas' => $columns];
                } finally { imagedestroy($canvas); }
            } finally { imagedestroy($source); }
        } finally { File::deleteDirectory($temporaryDirectory); }
    }

    /** Une tres columnas seleccionadas aunque pertenezcan a distintas hojas del PDF. */
    private function generateCompleteTableCapture(string $pdfPath, array $columns, string $temporaryDirectory): array
    {
        $tables = $this->findNumberedTablePages($pdfPath);
        $sources = [];
        $strips = [];
        try {
            $requiredTables = array_values(array_filter(
                $tables,
                static fn (array $table): bool => array_intersect($columns, $table['columns']) !== []
            ));
            foreach ($columns as $column) {
                if (!array_filter($requiredTables, static fn (array $table): bool => in_array($column, $table['columns'], true))) {
                    throw new RuntimeException("La columna {$column} no existe en las tablas numeradas del PDF.");
                }
            }

            foreach (array_values(array_unique(array_column($requiredTables, 'page'))) as $page) {
                $output = $temporaryDirectory . DIRECTORY_SEPARATOR . "page-{$page}.png";
                $this->renderPage($pdfPath, $output, $page);
                $source = @imagecreatefrompng($output);
                if ($source === false) {
                    throw new RuntimeException("No fue posible abrir la página {$page} del PDF.");
                }
                $pageTables = array_values(array_filter(
                    $tables,
                    static fn (array $table): bool => $table['page'] === $page
                ));
                $bounds = $this->detectTableSegmentBounds(
                    $source,
                    imagesx($source),
                    imagesy($source),
                    $pageTables
                );
                $sources[$page] = ['image' => $source];
                foreach ($pageTables as $position => $table) {
                    foreach ($tables as $tableIndex => $candidate) {
                        if ($candidate['page'] === $page && $candidate['segment'] === $table['segment']) {
                            $tables[$tableIndex]['bounds'] = $bounds[$position];
                            break;
                        }
                    }
                }
            }

            $columnFragments = [];
            foreach ($columns as $column) {
                $columnFragments[$column] = [];
                foreach ($tables as $table) {
                    $localPosition = array_search($column, $table['columns'], true);
                    if ($localPosition === false || empty($table['bounds']) || empty($sources[$table['page']])) continue;
                    $table['local_position'] = (int) $localPosition;
                    $table['source'] = $sources[$table['page']]['image'];
                    $columnFragments[$column][] = $table;
                }
            }

            $firstFragments = $columnFragments[$columns[0]];
            // Cada variante del equipo distribuye las columnas con anchos distintos; se usa la cuadrícula detectada.
            $labelsWidth = (int) $firstFragments[0]['bounds']['labels_width'];
            $columnWidth = (int) $firstFragments[0]['bounds']['column_width'];
            $labelStrip = $this->buildFragmentStrip($firstFragments, $labelsWidth);
            $strips[] = $labelStrip;
            foreach ($columns as $column) {
                $strips[$column] = $this->buildFragmentStrip(
                    $columnFragments[$column],
                    $columnWidth,
                    true
                );
            }

            $height = imagesy($labelStrip);
            $canvas = imagecreatetruecolor($labelsWidth + ($columnWidth * count($columns)), $height);
            if ($canvas === false) {
                throw new RuntimeException('No fue posible crear la imagen de los disparos seleccionados.');
            }

            try {
                $white = imagecolorallocate($canvas, 255, 255, 255);
                imagefill($canvas, 0, 0, $white);
                imagecopy($canvas, $labelStrip, 0, 0, 0, 0, $labelsWidth, $height);

                foreach ($columns as $position => $column) {
                    $strip = $strips[$column];
                    imagecopyresampled(
                        $canvas,
                        $strip,
                        $labelsWidth + ($position * $columnWidth),
                        0,
                        0,
                        0,
                        $columnWidth,
                        $height,
                        imagesx($strip),
                        imagesy($strip)
                    );
                }

                $canvasWidth = imagesx($canvas);
                $black = imagecolorallocate($canvas, 0, 0, 0);
                imagefilledrectangle($canvas, $canvasWidth - 2, 0, $canvasWidth - 1, $height - 1, $white);
                imageline($canvas, $canvasWidth - 1, 0, $canvasWidth - 1, $height - 1, $black);

                ob_start();
                imagepng($canvas, null, 6);
                $binary = ob_get_clean();
                if (!is_string($binary) || $binary === '') {
                    throw new RuntimeException('No fue posible codificar la captura de los disparos.');
                }

                return [
                    'data_url' => 'data:image/png;base64,' . base64_encode($binary),
                    'ancho' => imagesx($canvas),
                    'alto' => imagesy($canvas),
                    'columnas' => $columns,
                ];
            } finally {
                imagedestroy($canvas);
            }
        } finally {
            foreach ($strips as $strip) {
                if (is_object($strip) || is_resource($strip)) imagedestroy($strip);
            }
            foreach ($sources as $sourceData) {
                imagedestroy($sourceData['image']);
            }
        }
    }

    /** Construye una columna completa uniendo sus fragmentos y omitiendo encabezados repetidos. */
    private function buildFragmentStrip(array $fragments, int $width, bool $isNumberedColumn = false)
    {
        $pieces = [];
        $totalHeight = 0;
        foreach ($fragments as $index => $fragment) {
            $sourceX = $isNumberedColumn
                ? $fragment['bounds']['data_x'] + ($fragment['local_position'] * $fragment['bounds']['column_width'])
                : $fragment['bounds']['labels_x'];
            $sourceCropWidth = $isNumberedColumn
                ? $fragment['bounds']['column_width']
                : $fragment['bounds']['labels_width'];
            $skip = $index === 0 ? 0 : $fragment['bounds']['header_height'];
            $pieceHeight = $fragment['bounds']['height'] - $skip;
            $pieces[] = [
                'source' => $fragment['source'],
                'x' => $sourceX,
                'top' => $fragment['bounds']['top'] + $skip,
                'width' => $sourceCropWidth,
                'height' => $pieceHeight,
            ];
            $totalHeight += $pieceHeight;
        }

        $strip = imagecreatetruecolor($width, $totalHeight);
        if ($strip === false) throw new RuntimeException('No fue posible unir las partes de la tabla XRF.');
        $white = imagecolorallocate($strip, 255, 255, 255);
        imagefill($strip, 0, 0, $white);
        $destinationY = 0;
        foreach ($pieces as $piece) {
            imagecopyresampled(
                $strip,
                $piece['source'],
                0,
                $destinationY,
                $piece['x'],
                $piece['top'],
                $width,
                $piece['height'],
                $piece['width'],
                $piece['height']
            );
            $destinationY += $piece['height'];
        }

        return $strip;
    }

    /** Localiza todas las hojas que contienen columnas de disparos numeradas. */
    private function findNumberedTablePages(string $pdfPath): array
    {
        try {
            $pages = $this->parser->parseFile($pdfPath)->getPages();
        } catch (\Throwable $exception) {
            throw new RuntimeException('No fue posible revisar las páginas del PDF XRF.', 0, $exception);
        }

        $tables = [];
        foreach ($pages as $index => $page) {
            try {
                $segments = $this->analysisService->parseTableSegments($page->getText());
            } catch (\Throwable) {
                continue;
            }

            foreach ($segments as $segmentIndex => $segment) {
                $columns = array_values(array_map('intval', $segment['columnas'] ?? []));
                if ($columns === []) continue;
                $tables[] = [
                    'page' => $index + 1,
                    'segment' => $segmentIndex + 1,
                    'columns' => $columns,
                    'row_count' => count($segment['filas'] ?? []),
                    'rows' => array_column($segment['filas'] ?? [], 'elemento'),
                ];
            }
        }

        if ($tables === []) {
            throw new RuntimeException('No se encontraron tablas con disparos numerados en el PDF.');
        }

        return $tables;
    }

    /** Asocia cada bloque de texto detectado con su cuadrícula visual en la misma página. */
    private function detectTableSegmentBounds($source, int $pageWidth, int $pageHeight, array $segments): array
    {
        $left = max(0, (int) round($pageWidth * 0.045));
        $right = min($pageWidth - 1, (int) round($pageWidth * 0.858));
        $scanWidth = $right - $left + 1;
        $minimumDarkPixels = (int) round($scanWidth * 0.70);
        $bands = [];
        $bandStart = null;

        for ($y = (int) round($pageHeight * 0.05); $y <= (int) round($pageHeight * 0.95); $y++) {
            $darkPixels = 0;
            for ($x = $left; $x <= $right; $x++) {
                $rgb = imagecolorat($source, $x, $y);
                if ((($rgb >> 16) & 0xFF) < 100 && (($rgb >> 8) & 0xFF) < 100 && ($rgb & 0xFF) < 100) {
                    $darkPixels++;
                }
            }
            if ($darkPixels >= $minimumDarkPixels) {
                $bandStart ??= $y;
            } elseif ($bandStart !== null) {
                $bands[] = (int) round(($bandStart + $y - 1) / 2);
                $bandStart = null;
            }
        }

        $minimumGap = $pageHeight * 0.010;
        $maximumGap = $pageHeight * 0.020;
        $runs = [];
        $run = [];
        foreach ($bands as $band) {
            if ($run !== []) {
                $gap = $band - $run[array_key_last($run)];
                if ($gap < $minimumGap || $gap > $maximumGap) {
                    if (count($run) >= 3) $runs[] = $run;
                    $run = [];
                }
            }
            $run[] = $band;
        }
        if (count($run) >= 3) $runs[] = $run;

        $bounds = [];
        $runIndex = 0;
        foreach ($segments as $segment) {
            $requiredBands = ((int) $segment['row_count']) + 2;
            while (isset($runs[$runIndex]) && count($runs[$runIndex]) < $requiredBands) $runIndex++;
            if (!isset($runs[$runIndex])) {
                throw new RuntimeException('No fue posible relacionar todas las partes de la tabla con las páginas del PDF.');
            }
            $selectedBands = array_slice($runs[$runIndex], -$requiredBands);
            $top = max(0, $selectedBands[0] - 2);
            $bottom = min($pageHeight, $selectedBands[array_key_last($selectedBands)] + 3);
            $geometry = $this->detectTableHorizontalGeometry($source, $pageWidth, $top, $bottom);
            $bounds[] = array_merge([
                'top' => $top,
                'height' => $bottom - $top,
                'header_height' => $selectedBands[1] - $selectedBands[0],
            ], $geometry);
            $runIndex++;
        }

        return $bounds;
    }

    /** Detecta las divisiones verticales para respetar el ancho real de cada variante de tabla. */
    private function detectTableHorizontalGeometry($source, int $pageWidth, int $top, int $bottom): array
    {
        $fallback = [
            'labels_x' => (int) round($pageWidth * 0.046),
            'labels_width' => (int) round($pageWidth * 0.112),
            'data_x' => (int) round($pageWidth * 0.158),
            'column_width' => (int) round($pageWidth * 0.100),
        ];
        $height = max(1, $bottom - $top);
        // El umbral alto distingue las líneas verticales continuas de letras alineadas como '[' o '1'.
        $minimumDarkPixels = (int) round($height * 0.75);
        $bands = [];
        $bandStart = null;
        $left = (int) round($pageWidth * 0.02);
        $right = (int) round($pageWidth * 0.90);

        for ($x = $left; $x <= $right; $x++) {
            $darkPixels = 0;
            for ($y = $top; $y < $bottom; $y++) {
                $rgb = imagecolorat($source, $x, $y);
                if ((($rgb >> 16) & 0xFF) < 100 && (($rgb >> 8) & 0xFF) < 100 && ($rgb & 0xFF) < 100) {
                    $darkPixels++;
                }
            }
            if ($darkPixels >= $minimumDarkPixels) {
                $bandStart ??= $x;
            } elseif ($bandStart !== null) {
                $bands[] = (int) round(($bandStart + $x - 1) / 2);
                $bandStart = null;
            }
        }
        if ($bandStart !== null) {
            $bands[] = (int) round(($bandStart + $right) / 2);
        }

        if (count($bands) < 5) return $fallback;
        $labelsWidth = $bands[3] - $bands[0];
        $columnWidth = $bands[4] - $bands[3];
        if ($labelsWidth <= 0 || $columnWidth <= 0) return $fallback;

        return [
            'labels_x' => $bands[0],
            'labels_width' => $labelsWidth,
            'data_x' => $bands[3],
            'column_width' => $columnWidth,
        ];
    }

    /**
     * Detecta la secuencia larga de líneas horizontales de la tabla principal.
     * La tabla RSD es más angosta, por lo que no supera el ancho mínimo requerido.
     */
    private function detectCompleteTableBounds($source, int $pageWidth, int $pageHeight, int $rowCount): array
    {
        $left = max(0, (int) round($pageWidth * 0.045));
        $right = min($pageWidth - 1, (int) round($pageWidth * 0.858));
        $scanWidth = $right - $left + 1;
        $minimumDarkPixels = (int) round($scanWidth * 0.70);
        $bands = [];
        $bandStart = null;

        for ($y = (int) round($pageHeight * 0.15); $y <= (int) round($pageHeight * 0.90); $y++) {
            $darkPixels = 0;
            for ($x = $left; $x <= $right; $x++) {
                $rgb = imagecolorat($source, $x, $y);
                $red = ($rgb >> 16) & 0xFF;
                $green = ($rgb >> 8) & 0xFF;
                $blue = $rgb & 0xFF;
                if ($red < 100 && $green < 100 && $blue < 100) {
                    $darkPixels++;
                }
            }

            if ($darkPixels >= $minimumDarkPixels) {
                $bandStart ??= $y;
            } elseif ($bandStart !== null) {
                $bands[] = (int) round(($bandStart + $y - 1) / 2);
                $bandStart = null;
            }
        }
        if ($bandStart !== null) {
            $bands[] = (int) round(($bandStart + (int) round($pageHeight * 0.90)) / 2);
        }

        $minimumGap = $pageHeight * 0.010;
        $maximumGap = $pageHeight * 0.020;
        $bestStart = 0;
        $bestEnd = -1;
        $runStart = 0;

        for ($index = 1, $count = count($bands); $index < $count; $index++) {
            $gap = $bands[$index] - $bands[$index - 1];
            if ($gap < $minimumGap || $gap > $maximumGap) {
                if (($index - 1 - $runStart) > ($bestEnd - $bestStart)) {
                    $bestStart = $runStart;
                    $bestEnd = $index - 1;
                }
                $runStart = $index;
            }
        }
        if ($bands !== [] && (count($bands) - 1 - $runStart) > ($bestEnd - $bestStart)) {
            $bestStart = $runStart;
            $bestEnd = count($bands) - 1;
        }

        if ($bestEnd - $bestStart < 5) {
            throw new RuntimeException('No fue posible reconocer los límites de la tabla numerada del PDF XRF.');
        }

        // Una tabla con N elementos tiene N + 2 líneas horizontales:
        // borde superior, cierre del encabezado y cierre de cada elemento.
        $requiredBands = $rowCount + 2;
        $detectedBands = $bestEnd - $bestStart + 1;
        if ($rowCount < 1 || $detectedBands < $requiredBands) {
            throw new RuntimeException('La cuadrícula detectada no contiene todas las filas de elementos del PDF XRF.');
        }
        // Algunas versiones colocan otra tabla inmediatamente arriba. Se toman
        // las últimas N + 2 líneas porque la tabla RSD inferior es más angosta.
        $bestStart = $bestEnd - $requiredBands + 1;

        $top = max(0, $bands[$bestStart] - 2);
        $bottom = min($pageHeight, $bands[$bestEnd] + 3);

        return [$top, $bottom - $top];
    }

    /** Convierte una pagina especifica del PDF en una imagen PNG mediante Ghostscript. */
    private function renderPage(string $pdfPath, string $outputPath, int $page): void
    {
        $process = new Process([$this->detectGhostscriptBinary(), '-dSAFER', '-dBATCH', '-dNOPAUSE', '-dFirstPage=' . $page, '-dLastPage=' . $page, '-sDEVICE=png16m', '-r' . (int) config('xrf.render_dpi', 180), '-sOutputFile=' . $outputPath, $pdfPath]);
        $process->setTimeout(30);
        $process->run();
        if (!$process->isSuccessful() || !File::exists($outputPath)) throw new RuntimeException('Ghostscript no pudo convertir la página seleccionada del PDF en imagen.');
    }

    /** Localiza un ejecutable disponible de Ghostscript para renderizar el PDF. */
    private function detectGhostscriptBinary(): string
    {
        $configured = trim((string) config('xrf.ghostscript_binary', ''));
        $installed = glob('C:\\Program Files\\gs\\gs*\\bin\\gswin64c.exe') ?: [];
        usort($installed, static fn (string $a, string $b): int => strnatcasecmp($b, $a));
        foreach (array_values(array_filter(array_merge([$configured], $installed, ['gswin64c.exe', 'gswin32c.exe', 'gs']))) as $candidate) {
            if (str_contains($candidate, '\\') && File::exists($candidate)) return $candidate;
            if (!str_contains($candidate, '\\')) {
                try {
                    $probe = new Process([$candidate, '--version']);
                    $probe->setTimeout(5);
                    $probe->run();
                    if ($probe->isSuccessful()) return $candidate;
                } catch (\Throwable) {}
            }
        }
        throw new RuntimeException('No se encontró Ghostscript para convertir el PDF XRF.');
    }
}
