<?php

namespace Tests\Unit;

use Barryvdh\DomPDF\Facade\Pdf;
use Smalot\PdfParser\Parser;
use Tests\TestCase;

class FORPIMP0204DistribucionFotosTest extends TestCase
{
    public static function tamanosImagenes(): array
    {
        return [
            'verticales' => [300, 650, ['arriba_izquierda', 'arriba_derecha', 'abajo_izquierda', 'abajo_derecha']],
            'panoramicas' => [1200, 400, ['arriba_izquierda', 'arriba_derecha', 'abajo_izquierda', 'abajo_derecha']],
            'cuadradas' => [500, 500, ['arriba_izquierda', 'arriba_derecha', 'abajo_izquierda', 'abajo_derecha']],
            'esquinas_sin_asignar' => [300, 650, ['arriba_izquierda', 'abajo_derecha']],
            'pagina_completa' => [300, 650, ['pagina_completa']],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('tamanosImagenes')]
    public function test_fotos_y_comentarios_caben_en_una_hoja(int $ancho, int $alto, array $posiciones): void
    {
        $vertical = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="'.$ancho.'" height="'.$alto.'"><rect width="100%" height="100%" fill="#ddd"/></svg>');
        $fotos = [];
        foreach ($posiciones as $indice => $posicion) {
            $fotos[] = ['path' => $vertical, 'comment' => 'FOTO DE PRUEBA ' . $indice, 'pagina' => 1, 'posicion' => $posicion];
        }
        $datos = [
            'Fotos' => $fotos, 'Logo' => public_path('images/Logo_AICO_R.jpg'), 'QR_PDF' => null,
            'Detalles_Generales' => ['Partida' => str_repeat('INSPECCION DE PIEZAS Y COMPONENTES. ', 10)],
            'Datos_Equipo' => [], 'numFirmas' => 4, 'Firmas_Reportes' => [],
        ];
        $contenido = app(\App\Services\ServicioPdfGenerado::class)->generarAnexoFotos(
            'Reportes.ReportesFotosPDFIM.Reporte_FOTOS_FOR_PIMP_02_B_04_PDF', $datos, 8
        );
        $pdf = (new Parser())->parseContent($contenido);
        $this->assertCount(1, $pdf->getPages());
        foreach (array_keys($posiciones) as $indice) $this->assertStringContainsString('FOTO DE PRUEBA ' . $indice, $pdf->getText());
    }
}
