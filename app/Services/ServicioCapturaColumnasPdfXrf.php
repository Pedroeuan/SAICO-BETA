<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

class ServicioCapturaColumnasPdfXrf
{
    public function generate(UploadedFile $file, array $selectedColumns): array
    {
        if (!function_exists('imagecreatefrompng')) throw new RuntimeException('La extensión GD de PHP no está disponible.');
        $columns = array_values(array_unique(array_map('intval', $selectedColumns)));
        if (count($columns) !== 3 || array_filter($columns, static fn (int $column): bool => $column < 1 || $column > 7)) {
            throw new RuntimeException('Deben seleccionarse tres columnas válidas entre 1 y 7.');
        }

        $temporaryDirectory = storage_path('app/temp_xrf_columns/' . Str::uuid());
        File::makeDirectory($temporaryDirectory, 0755, true);
        $renderedPage = $temporaryDirectory . DIRECTORY_SEPARATOR . 'page.png';
        try {
            $this->renderFirstPage($file->getRealPath(), $renderedPage);
            $source = @imagecreatefrompng($renderedPage);
            if ($source === false) throw new RuntimeException('No fue posible abrir la primera página convertida del PDF.');
            try {
                $pageWidth = imagesx($source);
                $pageHeight = imagesy($source);
                $top = (int) round($pageHeight * 0.377);
                $height = (int) round($pageHeight * 0.421);
                $labelsX = (int) round($pageWidth * 0.046);
                $labelsWidth = (int) round($pageWidth * 0.112);
                $columnsX = (float) ($pageWidth * 0.158);
                $columnWidth = (int) round($pageWidth * 0.100);
                $canvas = imagecreatetruecolor($labelsWidth + ($columnWidth * 3), $height);
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

    private function renderFirstPage(string $pdfPath, string $outputPath): void
    {
        $process = new Process([$this->detectGhostscriptBinary(), '-dSAFER', '-dBATCH', '-dNOPAUSE', '-dFirstPage=1', '-dLastPage=1', '-sDEVICE=png16m', '-r' . (int) config('xrf.render_dpi', 180), '-sOutputFile=' . $outputPath, $pdfPath]);
        $process->setTimeout(30);
        $process->run();
        if (!$process->isSuccessful() || !File::exists($outputPath)) throw new RuntimeException('Ghostscript no pudo convertir la primera página del PDF en imagen.');
    }

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
