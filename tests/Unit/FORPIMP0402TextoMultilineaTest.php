<?php

namespace Tests\Unit;

use Barryvdh\DomPDF\Facade\Pdf;
use Smalot\PdfParser\Parser;
use Tests\TestCase;

class FORPIMP0402TextoMultilineaTest extends TestCase
{
    public static function plantillas(): array
    {
        return [
            'datos' => ['Reportes.ReportesPDFIM.Reporte_FOR_PIMP_04_02_PDF'],
            'fotos' => ['Reportes.ReportesFotosPDFIM.Reporte_FOTOS_FOR_PIMP_04_02_PDF'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('plantillas')]
    public function test_pdf_conserva_enter_y_lineas_vacias_en_los_cuatro_campos(string $plantilla): void
    {
        $html = view($plantilla, [
            'Detalles_Generales' => [
                'Proyecto' => "PROYECTO_PRIMERO\n\nPROYECTO_SEGUNDO",
                'Folio' => "FOLIO_PRIMERO\r\n\r\nFOLIO_SEGUNDO",
                'Partida' => "PARTIDA_PRIMERO\r\n\r\nPARTIDA_SEGUNDO",
                'Observaciones_Notas' => "NOTA_PRIMERO\r\rNOTA_SEGUNDO <marca>",
            ],
            'Datos_Equipo' => [], 'NormaIM' => ['Tabla' => []],
            'Logo' => '', 'QR_PDF' => '', 'CapturaXrf' => '',
            'Firmas_Reportes' => [], 'numFirmas' => 0,
            'Fotos' => [['es_cuadro_texto' => true, 'comment' => 'Foto de prueba']],
        ])->render();
        $this->assertStringContainsString('&lt;marca&gt;', $html);
        $pdf = Pdf::loadHTML($html)->setPaper('letter')->output();
        $documento = (new Parser)->parseContent($pdf);
        $posiciones = [];
        foreach ($documento->getPages() as $pagina => $hoja) {
            foreach ($hoja->getDataTm() as $texto) {
                if (preg_match('/(PROYECTO|FOLIO|PARTIDA|NOTA)_(PRIMERO|SEGUNDO)/', $texto[1], $m)) $posiciones[$m[0]] = ['pagina' => $pagina, 'y' => $texto[0][5]];
            }
        }
        foreach (['PROYECTO', 'FOLIO', 'PARTIDA', 'NOTA'] as $campo) {
            $this->assertArrayHasKey($campo.'_PRIMERO', $posiciones);
            $this->assertArrayHasKey($campo.'_SEGUNDO', $posiciones);
            $primero = $posiciones[$campo.'_PRIMERO']; $segundo = $posiciones[$campo.'_SEGUNDO'];
            $this->assertSame($primero['pagina'], $segundo['pagina']);
            $this->assertGreaterThanOrEqual(17, $primero['y'] - $segundo['y']);
        }
    }
}
