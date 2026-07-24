<?php

namespace Tests\Unit;

use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Tests\TestCase;

/** Detecta desbordamientos que provocarían una página adicional en el reporte principal. */
class FORPIMP0403EstructuraPdfTest extends TestCase
{
    /** Renderiza datos representativos y cuenta páginas para conservar el reporte principal en una hoja. */
    public function test_el_reporte_principal_cabe_en_una_sola_pagina(): void
    {
        $tabla = [];
        foreach (['C', 'Si', 'Mn', 'P', 'S', 'Cr', 'Mo', 'Ni', 'Al', 'Co'] as $elemento) {
            $tabla[] = [
                'Elemento' => $elemento,
                'Promedio' => '1.2345',
                'Composicion' => '0.10 - 2.50',
            ];
        }

        $contenido = Pdf::loadView('Reportes.ReportesPDFIM.Reporte_FOR_PIMP_04_03_PDF', [
            'Logo' => public_path('images/Logo_AICO_R.jpg'),
            'Detalles_Generales' => [
                'Fecha' => '2026-07-23',
                'No_Reporte' => 'PRUEBA-04-03',
                'Cliente' => 'CLIENTE DE PRUEBA',
                'Contrato' => 'CONTRATO-01',
                'Proyecto' => 'PROYECTO DE PRUEBA',
                'Observaciones_Notas' => 'SIN OBSERVACIONES',
            ],
            'Datos_Equipo' => [
                'VALORES_DUREZA' => range(100, 109),
                'PROMEDIO_DUREZA' => '104.50',
            ],
            'NormaIM' => ['Tabla' => $tabla],
            'Disparos' => [],
            'Firmas_Reportes' => ['Realizo' => 'REALIZÓ'],
            'numFirmas' => 1,
        ])->setPaper('letter', 'portrait')->output();

        $fpdi = new Fpdi();
        $this->assertSame(1, $fpdi->setSourceFile(StreamReader::createByString($contenido)));
    }
}
