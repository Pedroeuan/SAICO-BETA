<?php

namespace Tests\Unit;

use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Tests\TestCase;

/** Detecta desbordamientos que provocarían una página adicional en el reporte principal. */
class FORPIMP0403EstructuraPdfTest extends TestCase
{
    public static function plantillas(): array
    {
        return [
            'español' => ['Reportes.ReportesPDFIM.Reporte_FOR_PIMP_04_03_PDF'],
            'inglés' => ['Reportes.ReportesPDFIM.Reporte_FOR_PIMP_04_B_03_PDF'],
            'Olympus español' => ['Reportes.ReportesPDFIM.Reporte_FOR_PIMP_04_03_PDF', true],
            'Olympus inglés' => ['Reportes.ReportesPDFIM.Reporte_FOR_PIMP_04_B_03_PDF', true],
        ];
    }

    /** Renderiza datos representativos y cuenta páginas para conservar el reporte principal en una hoja. */
    #[\PHPUnit\Framework\Attributes\DataProvider('plantillas')]
    public function test_el_reporte_principal_cabe_en_una_sola_pagina(string $plantilla, bool $olympus = false): void
    {
        $tabla = [];
        foreach (['C', 'Si', 'Mn', 'P', 'S', 'Cr', 'Mo', 'Ni', 'Al', 'Co'] as $elemento) {
            $tabla[] = [
                'Elemento' => $elemento,
                'Promedio' => '1.2345',
                'Composicion' => '0.10 - 2.50',
            ];
        }

        $imagen = public_path('images/Logo_AICO_R.jpg');
        $documento = Pdf::loadView($plantilla, [
            'Logo' => public_path('images/Logo_AICO_R.jpg'),
            'Detalles_Generales' => [
                'Fecha' => '2026-07-23',
                'No_Reporte' => 'PRUEBA-04-03',
                'Cliente' => 'CLIENTE DE PRUEBA',
                'Contrato' => 'CONTRATO-01',
                'Proyecto' => 'PROYECTO DE PRUEBA',
                'Partida' => str_repeat('INSPECCIÓN DE MATERIAL Y CARACTERIZACIÓN METALOGRÁFICA. ', 6),
                'Observaciones_Notas' => 'SIN OBSERVACIONES',
            ],
            'Datos_Equipo' => [
                'VALORES_DUREZA' => range(100, 109),
                'PROMEDIO_DUREZA' => '104.50',
            ],
            'NormaIM' => ['Tabla' => $tabla],
            'Disparos' => [1 => [$imagen, $imagen], 2 => [$imagen, $imagen], 3 => [$imagen, $imagen]],
            'PerfilesDisparos' => $olympus ? [1 => 'olympus', 2 => 'olympus', 3 => 'olympus'] : [],
            'Firmas_Reportes' => ['Realizo' => 'REALIZÓ', 'Vobo1' => 'Vo. Bo.',
                'NOMBRE_TECNICO' => 'TÉCNICO DE PRUEBA', 'CARGO_TECNICO' => 'METALURGISTA',
                'EMPRESA_TECNICO' => 'ASESORÍA E INSPECCIÓN EN CONSTRUCCIÓN COSTA FUERA, S.C.',
                'NOMBRE_ENCARGADO' => 'RESPONSABLE DE CALIDAD', 'PUESTO_ENCARGADO' => 'SUPERVISOR'],
            'numFirmas' => 2,
        ])->setPaper('letter', 'portrait');
        $alturas = [];
        $documento->getDomPDF()->setCallbacks([['event' => 'end_frame', 'f' => function ($frame) use (&$alturas) {
            $nodo = $frame->get_node();
            if ($nodo instanceof \DOMElement && preg_match('/(?:^|\s)shot-image(?:\s|$)/', $nodo->getAttribute('class'))) {
                $alturas[] = $frame->get_border_box()['h'];
            }
        }]]);
        $contenido = $documento->output();
        // El borde debe mantener su altura, aunque la imagen Olympus sea menor.
        $this->assertCount(6, $alturas);
        foreach ($alturas as $altura) $this->assertGreaterThanOrEqual(4.7 * 72 / 2.54, $altura);

        $fpdi = new Fpdi();
        $this->assertSame(1, $fpdi->setSourceFile(StreamReader::createByString($contenido)));
    }
}
