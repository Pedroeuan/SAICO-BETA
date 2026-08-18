<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

class ServicioImagenesPdfXrf
{
    /** Áreas relativas de la tabla química y de la gráfica dentro de la hoja XRF. */
    private const DEFAULT_CROPS = [
        // La altura incluye la última fila química sin alcanzar la sección inferior de notas.
        'tabla_elementos' => ['x' => 0.054, 'y' => 0.225, 'width' => 0.410, 'height' => 0.400],
        'grafica_espectro' => ['x' => 0.575, 'y' => 0.105, 'width' => 0.395, 'height' => 0.305],
    ];

    /**
     * Coordenadas relativas al tamaño de la hoja. Esto mantiene el recorte
     * correcto aunque Ghostscript cambie la resolución de salida.
     */
    public function generateCrops(UploadedFile $file): array
    {
        if (!function_exists('imagecreatefrompng')) {
            throw new RuntimeException('La extensión GD de PHP no está disponible.');
        }

        $temporaryDirectory = storage_path('app/temp_xrf_crops/' . Str::uuid());
        File::makeDirectory($temporaryDirectory, 0755, true);
        $renderedPage = $temporaryDirectory . DIRECTORY_SEPARATOR . 'pagina.png';

        try {
            $this->renderFirstPage($file->getRealPath(), $renderedPage);
            $source = @imagecreatefrompng($renderedPage);

            if ($source === false) {
                throw new RuntimeException('No fue posible abrir la imagen generada desde el PDF.');
            }

            try {
                $pageWidth = imagesx($source);
                $pageHeight = imagesy($source);
                $results = [];

                $configuredCrops = config('xrf.crops');
                $crops = is_array($configuredCrops) && $configuredCrops !== []
                    ? $configuredCrops
                    : self::DEFAULT_CROPS;

                foreach ($crops as $type => $relative) {
                    $rectangle = [
                        'x' => (int) round($pageWidth * $relative['x']),
                        'y' => (int) round($pageHeight * $relative['y']),
                        'width' => (int) round($pageWidth * $relative['width']),
                        'height' => (int) round($pageHeight * $relative['height']),
                    ];

                    if ($type === 'tabla_elementos') {
                        $rectangle = $this->detectTableElementsRectangle($source, $pageWidth, $pageHeight, $rectangle);
                    }

                    $cropped = imagecrop($source, $rectangle);

                    if ($cropped === false) {
                        throw new RuntimeException("No fue posible recortar la sección {$type}.");
                    }

                    try {
                        ob_start();
                        imagepng($cropped, null, 6);
                        $binary = ob_get_clean();

                        if (!is_string($binary) || $binary === '') {
                            throw new RuntimeException("No fue posible codificar la sección {$type}.");
                        }

                        $results[$type] = [
                            'tipo' => $type,
                            'data_url' => 'data:image/png;base64,' . base64_encode($binary),
                            'ancho' => imagesx($cropped),
                            'alto' => imagesy($cropped),
                        ];
                    } finally {
                        imagedestroy($cropped);
                    }
                }

                return $results;
            } finally {
                imagedestroy($source);
            }
        } finally {
            File::deleteDirectory($temporaryDirectory);
        }
    }

    /**
     * Ajusta el recorte de la tabla química al borde negro real del PDF.
     *
     * El equipo XRF puede generar tablas con distinta cantidad de elementos
     * según la norma seleccionada. El recorte histórico usaba una altura fija,
     * por eso una tabla de 17 o 18 filas podía salir incompleta. Aquí se usa la
     * zona configurada solo como punto de partida y se detectan las líneas de la
     * cuadrícula para cerrar el recorte exactamente hasta la última fila.
     */
    private function detectTableElementsRectangle($source, int $pageWidth, int $pageHeight, array $fallback): array
    {
        /*
         * Primero se intenta detectar el bloque real de la tabla por contenido
         * visible (texto, grises, barras y bordes). Esto cubre PDFs del equipo
         * con 12, 15, 17 o 18 filas sin depender de una altura fija.
         */
        $contentRectangle = $this->detectTableElementsByContent($source, $pageWidth, $pageHeight, $fallback);

        if ($contentRectangle !== null) {
            return $contentRectangle;
        }

        $horizontalBands = $this->detectHorizontalTableBands($source, $pageWidth, $pageHeight, $fallback);

        if (count($horizontalBands) < 3) {
            return $this->normalizeRectangle($fallback, $pageWidth, $pageHeight);
        }

        $expectedTop = $fallback['y'];
        $topTolerance = (int) round($pageHeight * 0.045);
        $topCandidates = array_values(array_filter(
            $horizontalBands,
            static fn (int $band): bool => abs($band - $expectedTop) <= $topTolerance
        ));

        $top = $topCandidates !== []
            ? $this->nearestBand($topCandidates, $expectedTop)
            : $horizontalBands[0];

        $tableBands = array_values(array_filter(
            $horizontalBands,
            static fn (int $band): bool => $band >= $top
        ));

        $bottom = $this->detectBottomBandFromRun($tableBands, $pageHeight);
        [$left, $right] = $this->detectVerticalTableBorders($source, $pageWidth, $top, $bottom, $fallback);

        return $this->normalizeRectangle([
            'x' => $left - 2,
            'y' => $top - 2,
            'width' => ($right - $left) + 5,
            'height' => ($bottom - $top) + 5,
        ], $pageWidth, $pageHeight);
    }

    /**
     * Detecta la tabla por filas con pixeles no blancos.
     *
     * Algunos XRF dibujan la reticula en gris claro; por eso el detector de
     * lineas negras puede cortar la tabla antes de tiempo. Este metodo busca el
     * primer bloque continuo de contenido que inicia cerca del encabezado de la
     * tabla y termina antes del espacio blanco que separa la seccion de notas.
     */
    private function detectTableElementsByContent($source, int $pageWidth, int $pageHeight, array $fallback): ?array
    {
        $left = max(0, (int) round($fallback['x'] - ($pageWidth * 0.008)));
        $right = min($pageWidth - 1, (int) round($fallback['x'] + $fallback['width'] + ($pageWidth * 0.008)));
        $scanWidth = max(1, $right - $left + 1);
        // Umbral bajo: hay PDFs donde las filas inferiores tienen pocas marcas
        // negras, pero siguen perteneciendo a la misma tabla química.
        $minimumContentPixels = max(4, (int) round($scanWidth * 0.006));

        $startY = max(0, (int) round($fallback['y'] - ($pageHeight * 0.020)));
        $endY = min($pageHeight - 1, (int) round($fallback['y'] + max($fallback['height'] * 2.45, $pageHeight * 0.76)));
        $bands = [];
        $bandStart = null;
        $lastContentY = null;
        // Permite saltos internos pequeños entre texto, barras y líneas grises.
        // Un espacio grande separa la tabla de la sección de notas.
        $allowedGap = max(10, (int) round($pageHeight * 0.006));

        for ($y = $startY; $y <= $endY; $y++) {
            $contentPixels = 0;

            for ($x = $left; $x <= $right; $x++) {
                if ($this->isNonWhitePixel($source, $x, $y)) {
                    $contentPixels++;
                }
            }

            if ($contentPixels >= $minimumContentPixels) {
                if ($bandStart === null) {
                    $bandStart = $y;
                }

                $lastContentY = $y;
                continue;
            }

            if ($bandStart !== null && $lastContentY !== null && ($y - $lastContentY) > $allowedGap) {
                $bands[] = [$bandStart, $lastContentY];
                $bandStart = null;
                $lastContentY = null;
            }
        }

        if ($bandStart !== null && $lastContentY !== null) {
            $bands[] = [$bandStart, $lastContentY];
        }

        if ($bands === []) {
            return null;
        }

        $expectedTop = $fallback['y'];
        $topTolerance = (int) round($pageHeight * 0.050);
        $minimumBandHeight = max(40, (int) round($fallback['height'] * 0.45));
        $tableBand = null;

        foreach ($bands as $band) {
            [$bandTop, $bandBottom] = $band;
            $bandHeight = $bandBottom - $bandTop;

            if ($bandHeight < $minimumBandHeight) {
                continue;
            }

            if (abs($bandTop - $expectedTop) <= $topTolerance || ($bandTop <= $expectedTop && $bandBottom >= $expectedTop)) {
                $tableBand = $band;
                break;
            }
        }

        if ($tableBand === null) {
            return null;
        }

        [$top, $bottom] = $tableBand;
        [$left, $right] = $this->detectTableContentBounds($source, $pageWidth, $top, $bottom, $left, $right);

        return $this->normalizeRectangle([
            'x' => $left - 2,
            'y' => $top - 2,
            'width' => ($right - $left) + 5,
            'height' => ($bottom - $top) + 5,
        ], $pageWidth, $pageHeight);
    }

    /** Ajusta los bordes laterales al contenido de la tabla detectada. */
    private function detectTableContentBounds($source, int $pageWidth, int $top, int $bottom, int $fallbackLeft, int $fallbackRight): array
    {
        $height = max(1, $bottom - $top + 1);
        $minimumContentPixels = max(4, (int) round($height * 0.012));
        $first = null;
        $last = null;

        for ($x = $fallbackLeft; $x <= $fallbackRight; $x++) {
            $contentPixels = 0;

            for ($y = $top; $y <= $bottom; $y++) {
                if ($this->isNonWhitePixel($source, $x, $y)) {
                    $contentPixels++;
                }
            }

            if ($contentPixels >= $minimumContentPixels) {
                $first ??= $x;
                $last = $x;
            }
        }

        if ($first === null || $last === null || ($last - $first) < ($fallbackRight - $fallbackLeft) * 0.60) {
            return [$fallbackLeft, $fallbackRight];
        }

        return [
            max(0, $first),
            min($pageWidth - 1, $last),
        ];
    }

    /** Detecta líneas horizontales largas dentro de la zona probable de la tabla XRF. */
    private function detectHorizontalTableBands($source, int $pageWidth, int $pageHeight, array $fallback): array
    {
        $left = max(0, (int) round($fallback['x'] - ($pageWidth * 0.015)));
        $right = min($pageWidth - 1, (int) round($fallback['x'] + $fallback['width'] + ($pageWidth * 0.015)));
        $scanWidth = max(1, $right - $left + 1);
        $minimumDarkPixels = (int) round($scanWidth * 0.35);

        $startY = max(0, (int) round($fallback['y'] - ($pageHeight * 0.045)));
        // La tabla XRF cambia de altura segun la norma: algunas traen 12 filas
        // y otras pueden llegar a 17/18. Se escanea mas abajo que el recorte
        // fijo para detectar el borde final real sin depender de una medida unica.
        $endY = min($pageHeight - 1, (int) round($fallback['y'] + max($fallback['height'] * 2.25, $pageHeight * 0.70)));
        $bands = [];
        $bandStart = null;

        for ($y = $startY; $y <= $endY; $y++) {
            $darkPixels = 0;

            for ($x = $left; $x <= $right; $x++) {
                if ($this->isDarkPixel($source, $x, $y)) {
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
            $bands[] = (int) round(($bandStart + $endY) / 2);
        }

        return $bands;
    }

    /** Toma la última línea de la misma cuadrícula y evita brincar a otra sección del PDF. */
    private function detectBottomBandFromRun(array $bands, int $pageHeight): int
    {
        if (count($bands) <= 2) {
            return $bands[array_key_last($bands)] ?? 0;
        }

        /*
         * Algunas normas agregan filas o cambian la altura de ciertas filas.
         * Con un umbral de salto corto se cortaba la tabla alrededor de la fila 13.
         * Para la tabla de elementos es mas seguro tomar la ultima linea detectada
         * dentro de la zona de busqueda configurada; si el PDF trae 10 filas termina
         * antes, y si trae 17/18 filas el recorte baja hasta el borde real.
         */
        return $bands[array_key_last($bands)];
    }

    /** Detecta borde izquierdo y derecho para eliminar margen blanco lateral del recorte. */
    private function detectVerticalTableBorders($source, int $pageWidth, int $top, int $bottom, array $fallback): array
    {
        $height = max(1, $bottom - $top);
        $minimumDarkPixels = (int) round($height * 0.48);
        $startX = max(0, (int) round($fallback['x'] - ($pageWidth * 0.025)));
        $endX = min($pageWidth - 1, (int) round($fallback['x'] + $fallback['width'] + ($pageWidth * 0.025)));
        $bands = [];
        $bandStart = null;

        for ($x = $startX; $x <= $endX; $x++) {
            $darkPixels = 0;

            for ($y = $top; $y <= $bottom; $y++) {
                if ($this->isDarkPixel($source, $x, $y)) {
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
            $bands[] = (int) round(($bandStart + $endX) / 2);
        }

        if (count($bands) < 2) {
            return [$fallback['x'], $fallback['x'] + $fallback['width']];
        }

        return [$bands[0], $bands[array_key_last($bands)]];
    }

    private function nearestBand(array $bands, int $target): int
    {
        usort($bands, static fn (int $left, int $right): int => abs($left - $target) <=> abs($right - $target));

        return $bands[0];
    }

    private function normalizeRectangle(array $rectangle, int $pageWidth, int $pageHeight): array
    {
        $x = max(0, min($pageWidth - 1, (int) $rectangle['x']));
        $y = max(0, min($pageHeight - 1, (int) $rectangle['y']));
        $width = max(1, min($pageWidth - $x, (int) $rectangle['width']));
        $height = max(1, min($pageHeight - $y, (int) $rectangle['height']));

        return compact('x', 'y', 'width', 'height');
    }

    private function isDarkPixel($source, int $x, int $y): bool
    {
        $rgb = imagecolorat($source, $x, $y);

        return (($rgb >> 16) & 0xFF) < 115
            && (($rgb >> 8) & 0xFF) < 115
            && ($rgb & 0xFF) < 115;
    }

    private function isNonWhitePixel($source, int $x, int $y): bool
    {
        $rgb = imagecolorat($source, $x, $y);

        return (($rgb >> 16) & 0xFF) < 245
            || (($rgb >> 8) & 0xFF) < 245
            || ($rgb & 0xFF) < 245;
    }

    /** Renderiza únicamente la primera hoja porque el equipo genera un PDF de una hoja por disparo. */
    private function renderFirstPage(string $pdfPath, string $outputPath): void
    {
        $ghostscript = $this->detectGhostscriptBinary();
        $process = new Process([
            $ghostscript,
            '-dSAFER',
            '-dBATCH',
            '-dNOPAUSE',
            '-dFirstPage=1',
            '-dLastPage=1',
            '-sDEVICE=png16m',
            '-dTextAlphaBits=4',
            '-dGraphicsAlphaBits=4',
            '-r' . (int) config('xrf.render_dpi', 180),
            '-sOutputFile=' . $outputPath,
            $pdfPath,
        ]);
        $process->setTimeout(30);
        $process->run();

        if (!$process->isSuccessful() || !File::exists($outputPath)) {
            throw new RuntimeException('Ghostscript no pudo convertir el PDF en imagen: ' . trim($process->getErrorOutput()));
        }
    }

    /** Busca primero la ruta configurada y después las instalaciones conocidas de Ghostscript. */
    private function detectGhostscriptBinary(): string
    {
        $configuredBinary = trim((string) config('xrf.ghostscript_binary', ''));
        $installedWindowsBinaries = glob('C:\\Program Files\\gs\\gs*\\bin\\gswin64c.exe') ?: [];
        usort($installedWindowsBinaries, static fn (string $left, string $right): int => strnatcasecmp($right, $left));

        $candidates = array_values(array_filter(array_merge([
            $configuredBinary,
        ], $installedWindowsBinaries, [
            'gswin64c.exe',
            'gswin32c.exe',
            'gs',
        ])));

        foreach ($candidates as $candidate) {
            if (str_contains($candidate, '\\') && File::exists($candidate)) {
                return $candidate;
            }

            if (!str_contains($candidate, '\\')) {
                $probe = new Process([$candidate, '--version']);
                $probe->setTimeout(5);
                try {
                    $probe->run();
                    if ($probe->isSuccessful()) {
                        return $candidate;
                    }
                } catch (\Throwable) {
                    // Continúa con el siguiente nombre disponible.
                }
            }
        }

        throw new RuntimeException('No se encontró Ghostscript para convertir los PDF del equipo XRF.');
    }
}
