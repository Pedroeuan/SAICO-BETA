<?php

namespace Tests\Unit;

use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Tests\TestCase;

/** Comprueba que el anexo PDF respete cuadrantes, página completa y cuadros de texto. */
class FORPIMP0403PdfFotografiasTest extends TestCase
{
    /** Comprueba que cuadrantes y página completa generen páginas independientes en el HTML. */
    public function test_renderiza_fotos_en_cuadrantes_y_pagina_completa(): void
    {
        $html = view('Reportes.ReportesFotosPDFIM.Reporte_FOTOS_FOR_PIMP_04_03_PDF', [
            'Logo' => '',
            'Detalles_Generales' => ['No_Reporte' => 'PRUEBA-04-03'],
            'Datos_Equipo' => [],
            'Firmas_Reportes' => ['Realizo' => 'Realizo'],
            'numFirmas' => 1,
            'Fotos' => [
                [
                    'path' => 'foto-cuadrante.jpg',
                    'comment' => 'Cuadrante superior',
                    'pagina' => 1,
                    'posicion' => 'arriba_izquierda',
                    'una_hoja' => 0,
                ],
                [
                    'path' => 'foto-completa.jpg',
                    'comment' => 'Pagina completa',
                    'pagina' => 2,
                    'posicion' => 'pagina_completa',
                    'una_hoja' => 1,
                ],
            ],
        ])->render();

        $this->assertStringContainsString('foto-cuadrante.jpg', $html);
        $this->assertStringContainsString('foto-completa.jpg', $html);
        $this->assertStringContainsString('photo-slot photo-full', $html);
        $this->assertSame(2, substr_count($html, 'class="photo-page"'));
    }

    /** Detecta cambios de tamaño que harían desbordar cuatro fotografías a una segunda hoja. */
    public function test_cuatro_fotografias_caben_en_una_sola_pagina_de_anexo(): void
    {
        $posiciones = ['arriba_izquierda', 'arriba_derecha', 'abajo_izquierda', 'abajo_derecha'];
        $fotos = [];
        foreach ($posiciones as $posicion) {
            $fotos[] = [
                'path' => public_path('images/Logo_AICO_R.jpg'),
                'comment' => 'Fotografia de prueba',
                'pagina' => 1,
                'posicion' => $posicion,
                'una_hoja' => 0,
            ];
        }

        $contenido = Pdf::loadView('Reportes.ReportesFotosPDFIM.Reporte_FOTOS_FOR_PIMP_04_03_PDF', [
            'Logo' => public_path('images/Logo_AICO_R.jpg'),
            'Detalles_Generales' => ['No_Reporte' => 'PRUEBA-04-03'],
            'Datos_Equipo' => [],
            'Firmas_Reportes' => ['Realizo' => 'REALIZÓ'],
            'numFirmas' => 1,
            'Fotos' => $fotos,
        ])->setPaper('letter', 'portrait')->output();

        $fpdi = new Fpdi();
        $this->assertSame(1, $fpdi->setSourceFile(StreamReader::createByString($contenido)));
    }

    /** Garantiza que un cuadro de texto use el espacio fotográfico sin generar una etiqueta img vacía. */
    public function test_renderiza_un_cuadro_de_texto_en_la_posicion_de_una_fotografia(): void
    {
        $html = view('Reportes.ReportesFotosPDFIM.Reporte_FOTOS_FOR_PIMP_04_03_PDF', [
            'Logo' => '',
            'Detalles_Generales' => ['No_Reporte' => 'PRUEBA-04-03'],
            'Datos_Equipo' => [],
            'Firmas_Reportes' => ['Realizo' => 'Realizo'],
            'numFirmas' => 1,
            'Fotos' => [[
                'path' => null,
                'comment' => 'DESCRIPCIÓN DE LA MICROESTRUCTURA',
                'es_cuadro_texto' => 1,
                'pagina' => 1,
                'posicion' => 'arriba_derecha',
                'una_hoja' => 0,
            ]],
        ])->render();

        $this->assertStringContainsString('class="photo-text-box"', $html);
        $this->assertStringContainsString('DESCRIPCIÓN DE LA MICROESTRUCTURA', $html);
        $this->assertStringNotContainsString('<img src="" alt="Fotografía">', $html);
    }
}
