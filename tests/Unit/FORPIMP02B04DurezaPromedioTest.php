<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\IM\FOR_PIMP_02_B_04Controller;
use ReflectionMethod;
use Tests\TestCase;

class FORPIMP02B04DurezaPromedioTest extends TestCase
{
    /** El consecutivo calcula DESPUES sin modificar los valores historicos de ANTES. */
    public function test_promedio_posterior_conserva_los_valores_anteriores(): void
    {
        $controller = new FOR_PIMP_02_B_04Controller();
        $method = new ReflectionMethod($controller, 'calculateDurezaPromedio');
        $method->setAccessible(true);

        $resultado = $method->invoke($controller, [
            'ANTES_A' => '100',
            'ANTES_B' => '110',
            'ANTES_C' => '120',
            'ANTES_B1' => '130',
            'ANTES_BM' => '140',
            'DESPUES_A' => '',
            'DESPUES_B' => '',
            'DESPUES_C' => '',
            'DESPUES_B1' => '',
            'DESPUES_BM' => '',
            0 => [
                'metal_base_a' => '150',
                'zac_b' => '160',
                'soldadura_c' => '170',
                'zac_b1' => '180',
                'metal_base_a1' => '190',
            ],
            1 => [
                'metal_base_a' => '152',
                'zac_b' => '162',
                'soldadura_c' => '172',
                'zac_b1' => '182',
                'metal_base_a1' => '192',
            ],
        ], 'DESPUES');

        $this->assertSame('100', $resultado['ANTES_A']);
        $this->assertSame('110', $resultado['ANTES_B']);
        $this->assertSame('120', $resultado['ANTES_C']);
        $this->assertSame('130', $resultado['ANTES_B1']);
        $this->assertSame('140', $resultado['ANTES_BM']);
        $this->assertSame('151', $resultado['DESPUES_A']);
        $this->assertSame('161', $resultado['DESPUES_B']);
        $this->assertSame('171', $resultado['DESPUES_C']);
        $this->assertSame('181', $resultado['DESPUES_B1']);
        $this->assertSame('191', $resultado['DESPUES_BM']);
    }

    /** Las mediciones pendientes deben imprimirse como guiones y no como celdas vacias. */
    public function test_promedios_ausentes_se_convierten_en_guiones(): void
    {
        $controller = new FOR_PIMP_02_B_04Controller();
        $method = new ReflectionMethod($controller, 'completarPromediosDureza');
        $method->setAccessible(true);

        $resultado = $method->invoke($controller, [
            'ANTES_A' => '125',
            'DESPUES_A' => '',
        ]);

        $this->assertSame('125', $resultado['ANTES_A']);
        $this->assertSame('---', $resultado['DESPUES_A']);
        $this->assertSame('---', $resultado['DESPUES_B']);
        $this->assertSame('---', $resultado['DESPUES_C']);
        $this->assertSame('---', $resultado['DESPUES_B1']);
        $this->assertSame('---', $resultado['DESPUES_BM']);
    }

    /** Create, Edit y PDF deben usar la etapa y los nombres centrales configurables. */
    public function test_etapa_y_etiquetas_de_material_son_dinamicas(): void
    {
        $create = file_get_contents(resource_path('views/Reportes/IM/Create/FOR-PIMP-02_B_04.blade.php'));
        $edit = file_get_contents(resource_path('views/Reportes/IM/Edit/FOR-PIMP-02_B_04.blade.php'));
        $pdf = file_get_contents(resource_path('views/Reportes/ReportesPDFIM/Reporte_FOR_PIMP_02_B_04_PDF.blade.php'));
        $pdfFotos = file_get_contents(resource_path('views/Reportes/ReportesFotosPDFIM/Reporte_FOTOS_FOR_PIMP_02_B_04_PDF.blade.php'));
        $reporteController = file_get_contents(app_path('Http/Controllers/Reporte/ReporteController.php'));

        foreach ([$create, $edit] as $vista) {
            $this->assertStringContainsString('Datos_Equipo[DUREZA_ETAPA]', $vista);
            $this->assertStringContainsString('Datos_Equipo[ESCALA_DUREZA]', $vista);
            $this->assertStringContainsString('data-escala-dureza-vista', $vista);
            $this->assertStringContainsString('Datos_Equipo[ETIQUETA_MATERIAL_A]', $vista);
            $this->assertStringContainsString('Datos_Equipo[ETIQUETA_MATERIAL_A1]', $vista);
            $this->assertStringContainsString('data-catalogo-selector="escala"', $vista);
            $this->assertStringContainsString('data-catalogo-selector="material-a"', $vista);
            $this->assertStringContainsString('data-catalogo-selector="material-a1"', $vista);
            $this->assertStringContainsString('value="__nuevo__"', $vista);
            $this->assertStringNotContainsString('list="catalogoEscalasDureza0204"', $vista);
            $this->assertStringNotContainsString('list="catalogoMaterialesDureza0204"', $vista);
        }

        $this->assertStringContainsString('$tituloEtapaDureza[0]', $pdf);
        $this->assertStringContainsString('$etiquetaMaterialA', $pdf);
        $this->assertStringContainsString('$etiquetaMaterialA1', $pdf);
        $this->assertStringContainsString('$escalaDureza', $pdf);
        $this->assertStringContainsString('{{ $etiquetaMaterialA }}<br>(A)', $pdf);
        $this->assertStringContainsString('{{ $etiquetaMaterialA1 }}<br>(A1)', $pdf);
        $this->assertStringNotContainsString('ESCALA BRINELL', $pdf);
        $this->assertStringNotContainsString('Brinell Scale', $pdf);
        $this->assertStringNotContainsString('ANTES O DESPUES DEL RELEVADO DE ESFUERZOS<br>BEFORE OR AFTER PWHT', $pdf);
        $this->assertStringContainsString("\$Datos_Equipo['DUREZA_ETAPA']", $pdfFotos);
        $this->assertStringContainsString('EVIDENCIA FOTOGRÁFICA {{ $etapaFotograficaDureza[0] }}', $pdfFotos);
        $this->assertStringContainsString('PHOTOGRAPHIC EVIDENCE {{ $etapaFotograficaDureza[1] }}', $pdfFotos);
        $this->assertStringNotContainsString('EVIDENCIA FOTOGRÁFICA ANTES O DESPUÉS', $pdfFotos);
        $this->assertStringContainsString("['ETIQUETA_MATERIAL_A', 'ETIQUETA_MATERIAL_A1']", $reporteController);
        $this->assertStringContainsString("\$datos['ESCALA_DUREZA']", $reporteController);
        $this->assertStringContainsString('EscalasDureza0204', $reporteController);
    }
}
