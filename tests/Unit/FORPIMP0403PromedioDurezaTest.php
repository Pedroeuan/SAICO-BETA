<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\IM\FOR_PIMP_04_03Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use ReflectionMethod;
use Tests\TestCase;

/** Protege la tabla propia del formulario y el cálculo de dureza realizado en servidor. */
class FORPIMP0403PromedioDurezaTest extends TestCase
{
    /** Evita volver a mover las tablas a parciales y garantiza que Create y Edit tengan los campos requeridos. */
    public function test_create_y_edit_contienen_su_propia_tabla_de_dureza(): void
    {
        $create = file_get_contents(resource_path('views/Reportes/IM/Create/FOR-PIMP-04_03.blade.php'));
        $edit = file_get_contents(resource_path('views/Reportes/IM/Edit/FOR-PIMP-04_03.blade.php'));

        foreach ([$create, $edit] as $view) {
            $this->assertStringContainsString('Datos_Equipo[VALORES_DUREZA]', $view);
            $this->assertStringContainsString('Datos_Equipo[PROMEDIO_DUREZA]', $view);
            $this->assertStringNotContainsString('partials.hardness-material-tables', $view);
        }
    }  

    /** Comprueba el promedio de las diez lecturas y el formato final con dos decimales. */
    public function test_calcula_el_promedio_de_las_lecturas_numericas(): void
    {
        $request = Request::create('/', 'POST', [
            'Datos_Equipo' => [
                'VALORES_DUREZA' => ['100', '101', '102', '103', '104', '105', '106', '107', '108', '109'],
            ],
        ]);
        $datosEquipo = [];

        $this->invocarCalculo($request, $datosEquipo);

        $this->assertSame(['100', '101', '102', '103', '104', '105', '106', '107', '108', '109'], $datosEquipo['VALORES_DUREZA']);
        $this->assertSame('104.50', $datosEquipo['PROMEDIO_DUREZA']);
    }

    /** Conserva vacíos y guiones como valores sin medición y deja vacío el promedio. */
    public function test_acepta_la_tabla_de_dureza_sin_datos(): void
    {
        $request = Request::create('/', 'POST', [
            'Datos_Equipo' => [
                'VALORES_DUREZA' => ['---', '--', '', '-', '--', '', '---', '--', '', '--'],
            ],
        ]);
        $datosEquipo = [];

        $this->invocarCalculo($request, $datosEquipo);

        $this->assertSame(['---', '--', '', '-', '--', '', '---', '--', '', '--'], $datosEquipo['VALORES_DUREZA']);
        $this->assertSame('', $datosEquipo['PROMEDIO_DUREZA']);
    }

    /** Excluye los guiones del cálculo cuando la tabla también contiene lecturas numéricas. */
    public function test_promedia_solamente_las_celdas_numericas(): void
    {
        $request = Request::create('/', 'POST', [
            'Datos_Equipo' => ['VALORES_DUREZA' => ['100', '--', '', '110', '---']],
        ]);
        $datosEquipo = [];

        $this->invocarCalculo($request, $datosEquipo);

        $this->assertSame('105.00', $datosEquipo['PROMEDIO_DUREZA']);
    }

    /** Impide guardar valores no numéricos que producirían un promedio incorrecto. */
    public function test_rechaza_una_lectura_de_dureza_no_numerica(): void
    {
        $request = Request::create('/', 'POST', [
            'Datos_Equipo' => ['VALORES_DUREZA' => ['100', 'dato inválido']],
        ]);
        $datosEquipo = [];

        $this->expectException(ValidationException::class);
        $this->invocarCalculo($request, $datosEquipo);
    }

    /** Ejecuta el método privado del controlador sin cambiar su visibilidad en producción. */
    private function invocarCalculo(Request $request, array &$datosEquipo): void
    {
        $controller = new FOR_PIMP_04_03Controller();
        $method = new ReflectionMethod($controller, 'guardarPromedioDureza');
        $method->setAccessible(true);
        $arguments = [$request, &$datosEquipo];
        $method->invokeArgs($controller, $arguments);
    }
}
