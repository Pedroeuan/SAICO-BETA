<?php

namespace Tests\Unit;

use App\Services\ServicioImagenesPdfXrf;
use App\Services\ServicioAnalisisPdfXrf;
use Smalot\PdfParser\Parser;
use PHPUnit\Framework\TestCase;

class RecorteOlympusXrfTest extends TestCase
{
    public static function cantidades(): array { return [[4], [16], [32]]; }

    #[\PHPUnit\Framework\Attributes\DataProvider('cantidades')]
    public function test_recorta_las_filas_cuantificadas_sin_incluir_el_bloque_nd_ni_grado(int $filas): void
    {
        $imagen = imagecreatetruecolor(400, 1000);
        imagefill($imagen, 0, 0, imagecolorallocate($imagen, 255, 255, 255));
        $negro = imagecolorallocate($imagen, 0, 0, 0);
        imagefilledrectangle($imagen, 10, 20, 110, 28, $negro); // Grado: debe quedar fuera.
        $ultimo = 0;
        for ($i = 0; $i < $filas; $i++) {
            $y = 100 + $i * 18 + ($i >= 2 ? 45 : 0);
            imagefilledrectangle($imagen, 10, $y, 110, $y + 8, $negro);
            $ultimo = $y + 8;
        }
        imagefilledrectangle($imagen, 5, $ultimo + 50, 180, $ultimo + 100, $negro); // No detectado: fuera.
        try {
            $metodo = new \ReflectionMethod(ServicioImagenesPdfXrf::class, 'detectOlympusTableRectangle');
            $resultado = $metodo->invoke(new ServicioImagenesPdfXrf(new Parser), $imagen, 400, 1000,
                ['x' => 0, 'y' => 90, 'width' => 200, 'height' => 850, 'rows' => $filas, 'row_gap' => 0]);
            $this->assertLessThanOrEqual(100, $resultado['y']);
            $this->assertGreaterThan(28, $resultado['y']);
            $this->assertGreaterThan($ultimo, $resultado['y'] + $resultado['height']);
            $this->assertLessThan($ultimo + 50, $resultado['y'] + $resultado['height']);
        } finally { imagedestroy($imagen); }
    }

    public function test_reconoce_los_elementos_olympus_que_antes_no_se_leian(): void
    {
        $servicio = new ServicioAnalisisPdfXrf(new Parser);
        $resultado = $servicio->parseText("Pd ND < 0.02\nAg ND < 0.03\nCd 0.12 0.01");
        $this->assertSame('ND', $resultado['lecturas']['Pd']['calificador']);
        $this->assertSame('ND', $resultado['lecturas']['Ag']['calificador']);
        $this->assertSame(0.12, $resultado['lecturas']['Cd']['valor']);
    }

    public function test_identifica_el_perfil_por_el_contenido_del_equipo(): void
    {
        $servicio = new ServicioAnalisisPdfXrf(new Parser);
        $this->assertSame('olympus', $servicio->parseText("Modo de analizador: Alloy Plus\nE.lig. % +/- SS-303Se\nFe 71.71 0.10")['metadatos']['equipo']);
        $this->assertSame('vanta', $servicio->parseText("Serial Number: 123\nFe 71.71 0.10")['metadatos']['equipo']);
    }
}
