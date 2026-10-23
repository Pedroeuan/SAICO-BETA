<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * Entrega el QR del reporte en un formato que Dompdf pueda incrustar.
 *
 * Dompdf 3.1 no reconoce los SVG en PHP 8.5 porque getimagesize() ahora
 * devuelve IMAGETYPE_SVG y su tabla de tipos no lo contempla; el resultado
 * es un QR que se omite silenciosamente del PDF. Este servicio reconstruye
 * la matriz del QR con GD (sin Imagick) y conserva un PNG junto al original.
 */
class QrParaPdf
{
    /** Resolución de salida por módulo del QR. */
    private const PIXELES_POR_MODULO = 10;

    /** Resuelve una ruta de la base de datos y devuelve el archivo listo para Dompdf. */
    public function rutaParaDompdf(?string $rutaDb): ?string
    {
        $ruta = $this->resolverRuta($rutaDb);
        if ($ruta === null) {
            return null;
        }

        if (strtolower(pathinfo($ruta, PATHINFO_EXTENSION)) !== 'svg') {
            return $ruta;
        }

        return $this->convertirSvgAPng($ruta) ?? null;
    }

    /** Busca el archivo real aceptando las variantes históricas de la ruta guardada. */
    private function resolverRuta(?string $rutaDb): ?string
    {
        if ($rutaDb === null || trim($rutaDb) === '') {
            return null;
        }

        $ruta = trim(str_replace('\\', '/', $rutaDb));
        $candidatos = [];

        if (preg_match('#^([A-Za-z]:[\\/]|/)#', $ruta)) {
            $candidatos[] = $ruta;
        }

        $relativa = preg_replace('#^/?public/#', '', $ruta);
        $relativa = ltrim($relativa, '/');

        if (str_starts_with($relativa, 'storage/')) {
            $sinStorage = substr($relativa, strlen('storage/'));
            $candidatos[] = public_path($relativa);
            $candidatos[] = storage_path('app/public/' . $sinStorage);
            $candidatos[] = storage_path($sinStorage);
        } else {
            $candidatos[] = public_path($relativa);
            $candidatos[] = storage_path('app/public/' . $relativa);
            $candidatos[] = storage_path($relativa);
        }

        foreach (array_unique($candidatos) as $candidato) {
            if (is_file($candidato)) {
                return $candidato;
            }
        }

        return null;
    }

    /**
     * Reconstruye la matriz del QR a partir de las figuras del SVG y genera
     * un PNG nítido por módulo, sin depender del soporte SVG de Dompdf.
     */
    private function convertirSvgAPng(string $rutaSvg): ?string
    {
        $rutaPng = preg_replace('/\.svg$/i', '.png', $rutaSvg);

        if (is_file($rutaPng) && filemtime($rutaPng) >= filemtime($rutaSvg)) {
            return $rutaPng;
        }

        if (!function_exists('imagecreatetruecolor')) {
            Log::warning('QR de reporte: GD no está disponible para convertir el SVG.', ['ruta' => $rutaSvg]);
            return null;
        }

        try {
            $svg = simplexml_load_file($rutaSvg);
            if ($svg === false) {
                return null;
            }

            $poligonos = [];
            $this->recolectarPoligonos($svg, $poligonos);
            if ($poligonos === []) {
                return null;
            }

            $xs = [];
            $ys = [];
            foreach ($poligonos as $poligono) {
                foreach ($poligono as [$x, $y]) {
                    $xs[] = $x;
                    $ys[] = $y;
                }
            }

            $minX = min($xs);
            $minY = min($ys);
            $moduloX = $this->menorDiferencia($xs);
            $moduloY = $this->menorDiferencia($ys);
            if ($moduloX <= 0 || $moduloY <= 0) {
                return null;
            }

            $columnas = (int) round((max($xs) - $minX) / $moduloX);
            $filas = (int) round((max($ys) - $minY) / $moduloY);
            if ($columnas <= 0 || $filas <= 0) {
                return null;
            }

            $pixel = self::PIXELES_POR_MODULO;
            $imagen = imagecreatetruecolor($columnas * $pixel, $filas * $pixel);
            imagefilledrectangle(
                $imagen, 0, 0, $columnas * $pixel - 1, $filas * $pixel - 1,
                imagecolorallocate($imagen, 255, 255, 255)
            );
            $negro = imagecolorallocate($imagen, 0, 0, 0);

            for ($fila = 0; $fila < $filas; $fila++) {
                $centroY = $minY + ($fila + 0.5) * $moduloY;
                for ($columna = 0; $columna < $columnas; $columna++) {
                    $centroX = $minX + ($columna + 0.5) * $moduloX;
                    if ($this->estaDentro($centroX, $centroY, $poligonos)) {
                        imagefilledrectangle(
                            $imagen,
                            $columna * $pixel, $fila * $pixel,
                            ($columna + 1) * $pixel - 1, ($fila + 1) * $pixel - 1,
                            $negro
                        );
                    }
                }
            }

            $directorio = dirname($rutaPng);
            if (!File::exists($directorio)) {
                File::makeDirectory($directorio, 0777, true);
            }

            return imagepng($imagen, $rutaPng, 9) ? $rutaPng : null;
        } catch (\Throwable $error) {
            // El PDF debe generarse aunque el QR no pueda rasterizarse.
            Log::warning('QR de reporte: no fue posible convertir el SVG a PNG.', [
                'ruta' => $rutaSvg,
                'error' => $error->getMessage(),
            ]);
            return null;
        }
    }

    /** Devuelve la menor separación positiva entre coordenadas ordenadas. */
    private function menorDiferencia(array $valores): float
    {
        $valores = array_values(array_unique(array_map(static fn ($valor) => round((float) $valor, 4), $valores)));
        sort($valores, SORT_NUMERIC);
        $menor = 0.0;
        for ($indice = 1; $indice < count($valores); $indice++) {
            $diferencia = round($valores[$indice] - $valores[$indice - 1], 4);
            if ($diferencia > 0 && ($menor === 0.0 || $diferencia < $menor)) {
                $menor = $diferencia;
            }
        }
        return $menor;
    }

    /** Recorre el SVG y acumula las figuras oscuras (los huecos son blancos). */
    private function recolectarPoligonos(\SimpleXMLElement $nodo, array &$poligonos): void
    {
        foreach ($nodo->children() as $hijo) {
            $nombre = strtolower($hijo->getName());
            $atributos = $hijo->attributes();

            if ($nombre === 'g' || $nombre === 'svg') {
                $this->recolectarPoligonos($hijo, $poligonos);
                continue;
            }

            if (!$this->esRellenoOscuro((string) ($atributos['fill'] ?? ''))) {
                continue;
            }

            if ($nombre === 'rect') {
                $x = (float) ($atributos['x'] ?? 0);
                $y = (float) ($atributos['y'] ?? 0);
                $ancho = (float) ($atributos['width'] ?? 0);
                $alto = (float) ($atributos['height'] ?? 0);
                $poligonos[] = [[$x, $y], [$x + $ancho, $y], [$x + $ancho, $y + $alto], [$x, $y + $alto]];
                continue;
            }

            if ($nombre === 'path') {
                foreach ($this->poligonos((string) ($atributos['d'] ?? '')) as $poligono) {
                    $poligonos[] = $poligono;
                }
            }
        }
    }

    /** Solo interesan las figuras rellenas de negro; el fondo blanco se ignora. */
    private function esRellenoOscuro(string $fill): bool
    {
        $fill = strtolower(trim($fill));
        if ($fill === '' || $fill === 'currentcolor' || $fill === 'black' || $fill === '#000' || $fill === '#000000') {
            return true;
        }
        if (preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/', $fill, $coincidencia)) {
            $hex = $coincidencia[1];
            if (strlen($hex) === 3) {
                $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
            }
            $luminancia = (hexdec(substr($hex, 0, 2)) * 299
                + hexdec(substr($hex, 2, 2)) * 587
                + hexdec(substr($hex, 4, 2)) * 114) / 1000;
            return $luminancia < 128;
        }
        return false;
    }

    /**
     * Regla even-odd: los subcaminos interiores del patrón localizador
     * alternan relleno y hueco. Basta con contar cruces de todos los lados.
     */
    private function estaDentro(float $x, float $y, array $poligonos): bool
    {
        $dentro = false;
        foreach ($poligonos as $poligono) {
            $cantidad = count($poligono);
            for ($i = 0, $j = $cantidad - 1; $i < $cantidad; $j = $i++) {
                [$xi, $yi] = $poligono[$i];
                [$xj, $yj] = $poligono[$j];
                if ((($yi > $y) !== ($yj > $y))
                    && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi)) {
                    $dentro = !$dentro;
                }
            }
        }
        return $dentro;
    }

    /**
     * Extrae los polígonos cerrados de un atributo "d".
     * Soporta M/L/H/V/Z, que es lo que genera Simple QrCode.
     */
    private function poligonos(string $d): array
    {
        if (trim($d) === '') {
            return [];
        }

        preg_match_all('/[MmLlHhVvZz]|-?\d*\.?\d+(?:[eE][-+]?\d+)?/', $d, $tokens);
        $tokens = $tokens[0] ?? [];
        $poligonos = [];
        $actual = [];
        $x = 0.0;
        $y = 0.0;
        $inicioX = 0.0;
        $inicioY = 0.0;
        $comando = null;
        $indice = 0;

        $cerrar = function () use (&$actual, &$poligonos): void {
            if (count($actual) >= 3) {
                $poligonos[] = $actual;
            }
            $actual = [];
        };

        while ($indice < count($tokens)) {
            $token = $tokens[$indice];
            if (preg_match('/^[A-Za-z]$/', $token)) {
                $comando = $token;
                $indice++;
                if (strtoupper($comando) === 'Z') {
                    if ($actual !== []) {
                        $actual[] = [$inicioX, $inicioY];
                    }
                    $cerrar();
                    $x = $inicioX;
                    $y = $inicioY;
                }
                continue;
            }

            $relativo = ctype_lower($comando ?? 'M');
            $numero = (float) $token;
            $siguiente = $tokens[$indice + 1] ?? null;

            switch (strtoupper($comando ?? 'M')) {
                case 'M':
                    $x = $relativo ? $x + $numero : $numero;
                    $y = $relativo ? $y + (float) $siguiente : (float) $siguiente;
                    $inicioX = $x;
                    $inicioY = $y;
                    $actual = [[$x, $y]];
                    $comando = $relativo ? 'l' : 'L';
                    $indice += 2;
                    continue 2;
                case 'L':
                    $x = $relativo ? $x + $numero : $numero;
                    $y = $relativo ? $y + (float) $siguiente : (float) $siguiente;
                    $actual[] = [$x, $y];
                    $indice += 2;
                    continue 2;
                case 'H':
                    $x = $relativo ? $x + $numero : $numero;
                    $actual[] = [$x, $y];
                    $indice += 1;
                    continue 2;
                case 'V':
                    $y = $relativo ? $y + $numero : $numero;
                    $actual[] = [$x, $y];
                    $indice += 1;
                    continue 2;
                default:
                    // Comandos no usados por el QR: se ignoran sus argumentos.
                    $indice += 1;
                    continue 2;
            }
        }

        $cerrar();

        return $poligonos;
    }
}
