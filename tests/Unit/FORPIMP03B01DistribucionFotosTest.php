<?php

namespace Tests\Unit;

use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Smalot\PdfParser\Parser;
use Tests\TestCase;

/** Protege la distribución 2x2 del PDF metalográfico FOR-PIMP-03_B/01. */
class FORPIMP03B01DistribucionFotosTest extends TestCase
{
    /** Cuatro fotografías manuales deben conservarse en una sola hoja física. */
    public function test_cuatro_fotografias_manuales_caben_en_una_hoja(): void
    {
        $contenido = $this->generarPdf($this->crearCuatroFotografias());

        $fpdi = new Fpdi();
        $this->assertSame(1, $fpdi->setSourceFile(StreamReader::createByString($contenido)));
    }

    /** El cuadro de Fiji debe usar exactamente la misma celda que una fotografía. */
    public function test_resultado_fiji_conserva_la_cuadricula_y_su_borde(): void
    {
        $fotos = $this->crearCuatroFotografias();
        $fotos[1] = [
            'path' => null,
            'comment' => "RESULTADOS DEL ANÁLISIS METALOGRÁFICO\nUmbral: 0-85",
            'es_cuadro_texto' => 1,
            'pagina' => 1,
            'posicion' => 'arriba_derecha',
            'una_hoja' => 0,
        ];

        $html = view('Reportes.ReportesPDFIM.Reporte_FOR_PIMP_03_B_01_PDF', $this->datosVista($fotos))->render();

        $this->assertStringContainsString('foto-container-texto', $html);
        $this->assertStringContainsString('width: 47%;', $html);
        $this->assertStringContainsString('<col style="width:47%">', $html);
        $this->assertStringContainsString('height: 218px;', $html);
        $this->assertStringContainsString('height: 184px;', $html);
        $this->assertSame(2, substr_count($html, 'class="foto-separador"'));

        $contenido = $this->generarPdf($fotos);
        $fpdi = new Fpdi();
        $this->assertSame(
            1,
            $fpdi->setSourceFile(StreamReader::createByString($contenido))
        );
        $textoPdf = (new Parser())->parseContent($contenido)->getText();
        $this->assertStringContainsString('RESULTADOS DEL ANÁLISIS METALOGRÁFICO', $textoPdf);
    }

    /** La variante más alta de firmas debe permanecer dentro de la misma hoja. */
    public function test_cuatro_firmas_y_cuatro_fotografias_caben_en_una_hoja(): void
    {
        $datos = $this->datosVista($this->crearCuatroFotografias());
        $datos['numFirmas'] = 4;
        $datos['Firmas_Reportes'] = [
            'Realizo' => 'REALIZÓ',
            'Vobo1' => 'Vo.Bo.',
            'Vobo2' => 'Vo.Bo.',
            'Vobo3' => 'Vo.Bo.',
            'NOMBRE_TECNICO' => 'TÉCNICO',
            'NOMBRE_ENCARGADO' => 'ENCARGADO 1',
            'NOMBRE_2DO_ENCARGADO' => 'ENCARGADO 2',
            'NOMBRE_3RO_ENCARGADO' => 'ENCARGADO 3',
        ];

        $contenido = Pdf::loadView(
            'Reportes.ReportesPDFIM.Reporte_FOR_PIMP_03_B_01_PDF',
            $datos
        )->setPaper('letter', 'portrait')->output();

        $fpdi = new Fpdi();
        $this->assertSame(1, $fpdi->setSourceFile(StreamReader::createByString($contenido)));
    }

    /** Construye las cuatro posiciones que el usuario asigna manualmente. */
    private function crearCuatroFotografias(): array
    {
        $fotos = [];
        foreach (['arriba_izquierda', 'arriba_derecha', 'abajo_izquierda', 'abajo_derecha'] as $posicion) {
            $fotos[] = [
                // Una fotografía horizontal reproduce el uso normal de este formato.
                'path' => public_path('images/MenuServicios/RELEVADO_DE_ESFUERZO.png'),
                'comment' => 'FOTOGRAFÍA DE PRUEBA',
                'pagina' => 1,
                'posicion' => $posicion,
                'una_hoja' => 0,
            ];
        }

        return $fotos;
    }

    /** Genera el PDF real para detectar desbordamientos propios de DomPDF. */
    private function generarPdf(array $fotos): string
    {
        return Pdf::loadView(
            'Reportes.ReportesPDFIM.Reporte_FOR_PIMP_03_B_01_PDF',
            $this->datosVista($fotos)
        )->setPaper('letter', 'portrait')->output();
    }

    /** Proporciona los datos mínimos requeridos por la plantilla principal. */
    private function datosVista(array $fotos): array
    {
        return [
            'Logo' => public_path('images/Logo_AICO_R.jpg'),
            'Detalles_Generales' => ['No_Reporte' => 'PRUEBA-03-B-01'],
            'Datos_Equipo' => [],
            'Firmas_Reportes' => [],
            'numFirmas' => 1,
            'Fotos' => $fotos,
        ];
    }
}
