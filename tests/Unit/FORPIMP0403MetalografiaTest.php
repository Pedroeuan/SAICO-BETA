<?php

namespace Tests\Unit;

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
}
