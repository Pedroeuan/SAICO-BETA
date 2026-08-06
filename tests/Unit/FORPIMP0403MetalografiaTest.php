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

    /** Create y Edit de 06 deben ofrecer exactamente el mismo flujo de tamaño de grano. */
    public function test_06_b_01_comparte_el_patron_de_grano_y_no_muestra_detalles_de_junta(): void
    {
        $create = file_get_contents(resource_path('views/Reportes/IM/Create/FOR-PIMP-06_B_01.blade.php'));
        $edit = file_get_contents(resource_path('views/Reportes/IM/Edit/FOR-PIMP-06_B_01.blade.php'));
        $scriptGrano = file_get_contents(public_path('js/patron-grano-reporte.js'));
        $scriptImagenes = file_get_contents(public_path('js/Reportes_Create-For-02-06-IM.js'));

        foreach ([$create, $edit] as $vista) {
            $this->assertStringContainsString('partials.patron-grano-reporte', $vista);
            $this->assertStringContainsString('js/patron-grano-reporte.js', $vista);
            $this->assertStringNotContainsString('id="inputSuccess"', $vista);
        }

        $this->assertStringContainsString("'FOR-PIMP-06_B_01'", $scriptGrano);
        $this->assertStringContainsString('${!permiteDisparos ?', $scriptImagenes);
        $this->assertStringContainsString('Descripción para este reporte', $scriptGrano);
    }

    /** El controlador 06 guarda, actualiza y envía al anexo la copia histórica del grano. */
    public function test_06_b_01_conserva_el_patron_historico_hasta_el_pdf(): void
    {
        $controlador = file_get_contents(app_path('Http/Controllers/Reporte/IM/FOR_PIMP_06_B_01Controller.php'));

        $this->assertStringContainsString('ServicioPatronGranoReporte', $controlador);
        $this->assertSame(2, substr_count($controlador, '->construirHistorico('));
        $this->assertStringContainsString('->eliminarCopiaSustituida(', $controlador);
        $this->assertStringContainsString('->agregarAlPdf($Fotos, $Detalles_Generales)', $controlador);
    }

    /** El espacio de descripción del 06 se conserva sin imagen desde Create hasta el anexo. */
    public function test_06_b_01_permite_un_cuadro_de_descripcion_sin_archivo(): void
    {
        $scriptLayout = file_get_contents(public_path('js/Reportes_Fotos_Posicionables_02_B_04.js'));
        $controlador = file_get_contents(app_path('Http/Controllers/Reporte/IM/FOR_PIMP_06_B_01Controller.php'));
        $anexo = file_get_contents(resource_path('views/Reportes/ReportesFotosPDFIM/Reporte_FOTOS_FOR_PIMP_06_B_01_PDF.blade.php'));

        $this->assertStringContainsString("'FOR-PIMP-06_B_01'", $scriptLayout);
        $this->assertStringContainsString('Usar este espacio como descripción para el reporte', $scriptLayout);
        $this->assertStringContainsString("'foto_es_texto' => 'nullable|array'", $controlador);
        $this->assertStringContainsString("'es_cuadro_texto' => 1", $controlador);
        $this->assertStringContainsString("!empty(\$foto['es_cuadro_texto'])", $controlador);
        $this->assertStringContainsString("!empty(\$espacios[\$posicion]['es_cuadro_texto'])", $anexo);
        $this->assertStringContainsString('descripcion-reporte', $anexo);
    }

    /** Una fotografia opcional sin ruta no debe convertir la carpeta publica en una imagen de Dompdf. */
    public function test_06_b_01_descarta_rutas_vacias_antes_de_generar_el_pdf(): void
    {
        $controlador = file_get_contents(app_path('Http/Controllers/Reporte/IM/FOR_PIMP_06_B_01Controller.php'));
        $servicioGrano = file_get_contents(app_path('Services/ServicioPatronGranoReporte.php'));

        $this->assertStringContainsString("if (\$rutaGuardada === '')", $controlador);
        $this->assertStringContainsString('File::isFile($rutaFoto)', $controlador);
        $this->assertStringContainsString('File::isFile($rutaFisica)', $servicioGrano);
    }

    /** La cuadricula logica de cuatro espacios del 06 debe caber en una sola hoja fisica. */
    public function test_06_b_01_no_divide_una_cuadricula_de_cuatro_fotos(): void
    {
        $imagen = public_path('images/MenuServicios/RELEVADO_DE_ESFUERZO.png');
        $posiciones = ['arriba_izquierda', 'arriba_derecha', 'abajo_izquierda', 'abajo_derecha'];
        $fotos = [];

        foreach ($posiciones as $posicion) {
            $fotos[] = [
                'path' => $imagen,
                'comment' => 'EVIDENCIA FOTOGRAFICA',
                'pagina' => 1,
                'posicion' => $posicion,
            ];
        }

        $contenido = Pdf::loadView('Reportes.ReportesFotosPDFIM.Reporte_FOTOS_FOR_PIMP_06_B_01_PDF', [
            'Logo' => public_path('images/Logo_AICO_R.jpg'),
            'Detalles_Generales' => ['No_Reporte' => 'QA-06'],
            'Firmas_Reportes' => [],
            'numFirmas' => 1,
            'Fotos' => $fotos,
        ])->setPaper('letter', 'portrait')->output();

        $fpdi = new Fpdi();
        $this->assertSame(
            1,
            $fpdi->setSourceFile(StreamReader::createByString($contenido)),
            'Las dos filas del 06 no deben repartirse entre dos hojas fisicas.'
        );
    }
}
