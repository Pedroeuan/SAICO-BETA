<?php

namespace Tests\Unit;

use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Smalot\PdfParser\Parser;
use Tests\TestCase;

/** Asegura que Create, Edit y el anexo conserven los datos metalográficos. */
class FORPIMP0403MetalografiaTest extends TestCase
{
    /** Verifica que los campos pertenezcan directamente a Create y Edit y que Edit recupere datos guardados. */
    public function test_create_y_edit_contienen_sus_propios_campos_metalograficos(): void
    {
        $create = file_get_contents(resource_path('views/Reportes/IM/Create/FOR-PIMP-04_03.blade.php'));
        $edit = file_get_contents(resource_path('views/Reportes/IM/Edit/FOR-PIMP-04_03.blade.php'));

        foreach (['MATERIAL_PANO', 'MATERIAL_ABRASIVO', 'REACTIVO', 'TIEMPO_ATAQUE', 'FASES_PRESENTES', 'ESPECIFICACION_MATERIAL'] as $campo) {
            $this->assertStringContainsString("Datos_Equipo[$campo]", $create);
            $this->assertStringContainsString("Datos_Equipo[$campo]", $edit);
        }

        $this->assertStringNotContainsString('partials.metallographic-analysis-form', $create);
        $this->assertStringNotContainsString('partials.metallographic-analysis-form', $edit);
        $this->assertStringContainsString("\$Datos_Equipo['MATERIAL_PANO']", $edit);
    }

    /** Confirma que la tabla metalográfica y sus valores aparezcan en la primera hoja del anexo. */
    public function test_la_primera_hoja_de_fotos_incluye_la_tabla_metalografica(): void
    {
        $html = view('Reportes.ReportesFotosPDFIM.Reporte_FOTOS_FOR_PIMP_04_03_PDF', [
            'Logo' => '',
            'Detalles_Generales' => [],
            'Datos_Equipo' => [
                'MATERIAL_PANO' => 'FIELTRO',
                'REACTIVO' => 'NITAL 2%',
            ],
            'Firmas_Reportes' => ['Realizo' => 'Realizo'],
            'numFirmas' => 1,
            'Fotos' => [[
                'path' => 'fotomicrografia.jpg',
                'pagina' => 1,
                'posicion' => 'arriba_izquierda',
            ]],
        ])->render();

        $this->assertStringContainsString('ANÁLISIS METALOGRÁFICO', $html);
        $this->assertStringContainsString('FIELTRO', $html);
        $this->assertStringContainsString('NITAL 2%', $html);
    }

    /** Los dos anexos reutilizables deben admitir Fiji, grano y pieza sin repartirlos en otra hoja. */
    public function test_fiji_grano_y_pieza_caben_en_una_hoja_en_04_02_y_04_03(): void
    {
        $imagen = public_path('images/MenuServicios/RELEVADO_DE_ESFUERZO.png');
        $fotos = [
            ['path' => $imagen, 'comment' => 'FOTOMICROGRAFÍA ANALIZADA', 'pagina' => 1, 'posicion' => 'arriba_izquierda'],
            [
                'path' => null,
                'comment' => "RESULTADOS DEL ANÁLISIS METALOGRÁFICO\nUmbral: 0-85\nPromedio: 6.500 granos por línea.",
                'es_cuadro_texto' => 1,
                'pagina' => 1,
                'posicion' => 'arriba_derecha',
            ],
            ['path' => $imagen, 'comment' => 'TAMAÑO DE GRANO 3.5', 'pagina' => 1, 'posicion' => 'abajo_izquierda'],
            ['path' => $imagen, 'comment' => 'FOTOGRAFÍA DE LA PIEZA', 'pagina' => 1, 'posicion' => 'abajo_derecha'],
        ];
        $datos = [
            'Logo' => public_path('images/Logo_AICO_R.jpg'),
            'Detalles_Generales' => ['No_Reporte' => 'QA-04'],
            'Datos_Equipo' => [],
            'Firmas_Reportes' => [],
            'numFirmas' => 1,
            'Fotos' => $fotos,
        ];

        foreach (['04_02', '04_03'] as $formato) {
            $contenido = Pdf::loadView(
                "Reportes.ReportesFotosPDFIM.Reporte_FOTOS_FOR_PIMP_{$formato}_PDF",
                $datos
            )->setPaper('letter', 'portrait')->output();

            $fpdi = new Fpdi();
            $this->assertSame(
                1,
                $fpdi->setSourceFile(StreamReader::createByString($contenido)),
                "El anexo {$formato} no debe dividir la cuadrícula en otra hoja."
            );
            $texto = (new Parser())->parseContent($contenido)->getText();
            $this->assertStringContainsString('RESULTADOS DEL ANÁLISIS METALOGRÁFICO', $texto);
            $this->assertStringContainsString('TAMAÑO DE GRANO 3.5', $texto);
        }
    }
}
