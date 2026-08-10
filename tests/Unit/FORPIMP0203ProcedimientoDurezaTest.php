<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\IM\FOR_PIMP_02_B_03Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use ReflectionMethod;
use Tests\TestCase;

/** Verifica el procedimiento automatico IM y el promedio aislado del formato 02_B_03. */
class FORPIMP0203ProcedimientoDurezaTest extends TestCase
{
    /** Todos los formatos IM deben guardar el mismo procedimiento asignado que muestra PINS. */
    public function test_create_y_edit_im_usan_procedimiento_asignado_de_solo_lectura(): void
    {
        $formatos = [
            'FOR-PIMP-02_B_03',
            'FOR-PIMP-02_B_04',
            'FOR-PIMP-03_B_01',
            'FOR-PIMP-04_02',
            'FOR-PIMP-04_03',
            'FOR-PIMP-05_01',
            'FOR-PIMP-05_B_01',
            'FOR-PIMP-06_B_01',
            'FOR-PIMP-07_B_01',
        ];

        foreach ($formatos as $formato) {
            foreach (['Create', 'Edit'] as $accion) {
                $vista = file_get_contents(resource_path("views/Reportes/IM/{$accion}/{$formato}.blade.php"));

                $this->assertStringContainsString('Detalles_Generales[Procedimiento]', $vista, "Falta procedimiento en {$accion} {$formato}");
                $this->assertStringContainsString('Detalles_Generales[idProcedimiento]', $vista, "Falta idProcedimiento en {$accion} {$formato}");
                $this->assertMatchesRegularExpression('/name="Detalles_Generales\[Procedimiento\]".*readonly/', $vista);
            }

            $controlador = str_replace(['FOR-PIMP-', '-'], ['FOR_PIMP_', '_'], $formato) . 'Controller.php';
            $codigoControlador = file_get_contents(app_path("Http/Controllers/Reporte/IM/{$controlador}"));
            $this->assertSame(
                2,
                substr_count($codigoControlador, "'Detalles_Generales.idProcedimiento'"),
                "Store y Update deben aceptar idProcedimiento en {$formato}"
            );
        }
    }

    /** El promedio considera todas las columnas, ignora ausencias y redondea a entero. */
    public function test_promedio_02_b_03_es_entero_y_se_calcula_en_servidor(): void
    {
        $request = Request::create('/', 'POST', [
            'valor_dureza1' => ['sin_titulo' => ['100', '---']],
            'valor_dureza2' => ['sin_titulo' => ['101', '']],
            'valor_dureza3' => ['sin_titulo' => ['102']],
            'valor_dureza4' => ['sin_titulo' => ['103']],
            'valor_dureza5' => ['sin_titulo' => ['106']],
        ]);

        $this->assertSame('102', $this->calcular($request));
    }

    /** Una captura no numerica debe impedir guardar un promedio silenciosamente incorrecto. */
    public function test_promedio_02_b_03_rechaza_lecturas_invalidas(): void
    {
        $this->expectException(ValidationException::class);

        $this->calcular(Request::create('/', 'POST', [
            'valor_dureza1' => ['sin_titulo' => ['ABC']],
        ]));
    }

    /** La escala permanece editable y el PDF deja de estar amarrado a Brinell. */
    public function test_escala_02_b_03_es_dinamica_en_formularios_y_pdf(): void
    {
        $create = file_get_contents(resource_path('views/Reportes/IM/Create/FOR-PIMP-02_B_03.blade.php'));
        $edit = file_get_contents(resource_path('views/Reportes/IM/Edit/FOR-PIMP-02_B_03.blade.php'));
        $pdf = file_get_contents(resource_path('views/Reportes/ReportesPDFIM/Reporte_FOR_PIMP_02_B_03_PDF.blade.php'));
        $script = file_get_contents(public_path('js/promedio-dureza-02-b-03.js'));

        foreach ([$create, $edit] as $vista) {
            $this->assertStringContainsString('Datos_Equipo[ESCALA_DUREZA]', $vista);
            $this->assertStringContainsString('Datos_Equipo[DUREZA_PROMEDIO_MEDIDO]', $vista);
            $this->assertStringContainsString('promedio-dureza-02-b-03.js', $vista);
            $this->assertStringNotContainsString('BRINELL', $vista, 'La escala debe iniciar vacia y ser capturada por el tecnico.');
        }

        $this->assertStringContainsString("Datos_Equipo['ESCALA_DUREZA']", $pdf);
        $this->assertStringNotContainsString('BRINELL', $pdf, 'El PDF no debe sustituir una escala no capturada.');
        $this->assertStringContainsString('Math.round', $script);
    }

    /** Ningun formulario, controlador o PDF de IM debe conservar datos del soldador. */
    public function test_datos_del_soldador_fueron_retirados_de_todos_los_formatos_im(): void
    {
        $directorios = [
            resource_path('views/Reportes/IM'),
            resource_path('views/Reportes/ReportesPDFIM'),
            resource_path('views/Reportes/ReportesFotosPDFIM'),
            app_path('Http/Controllers/Reporte/IM'),
        ];

        foreach ($directorios as $directorio) {
            $archivos = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directorio));

            foreach ($archivos as $archivo) {
                if (!$archivo->isFile() || !in_array($archivo->getExtension(), ['php'], true)) {
                    continue;
                }

                $contenido = file_get_contents($archivo->getPathname());
                $this->assertStringNotContainsString('Num_Soldador', $contenido, $archivo->getPathname());
                $this->assertStringNotContainsString('Nombre_Soldador', $contenido, $archivo->getPathname());
                $this->assertStringNotContainsString('DATOS SOLDADOR', $contenido, $archivo->getPathname());
            }
        }
    }

    /** Invoca la funcion privada sin pasar por rutas ni persistencia. */
    private function calcular(Request $request): string
    {
        $metodo = new ReflectionMethod(FOR_PIMP_02_B_03Controller::class, 'calcularPromedioDurezaEntero');
        $metodo->setAccessible(true);

        return $metodo->invoke(new FOR_PIMP_02_B_03Controller(), $request);
    }
}
