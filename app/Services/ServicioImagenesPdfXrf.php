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
