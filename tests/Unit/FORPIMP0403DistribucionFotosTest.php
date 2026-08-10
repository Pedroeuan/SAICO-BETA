<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\IM\FOR_PIMP_04_03Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use ReflectionMethod;
use Tests\TestCase;

/** Verifica distribución fotográfica, cuadros de texto y pares de disparos manuales. */
class FORPIMP0403DistribucionFotosTest extends TestCase
{
    /** Verifica que una selección explícita de página completa no sea reemplazada por valores automáticos. */
    public function test_conserva_la_hoja_y_posicion_seleccionadas(): void
    {
        $this->assertSame([
            'pagina' => 3,
            'posicion' => 'pagina_completa',
            'una_hoja' => 1,
        ], $this->normalizar(3, 'pagina_completa', 0));
    }

    /** Confirma el acomodo automático por grupos de cuatro cuando el navegador no envía posición. */
    public function test_asigna_una_distribucion_valida_si_no_se_envia_posicion(): void
    {
        $this->assertSame([
            'pagina' => 2,
            'posicion' => 'arriba_derecha',
            'una_hoja' => 0,
        ], $this->normalizar(null, null, 5));
    }

    /** Los identificadores 1000+ de los recortes XRF no deben convertirse en hojas 251 o superiores. */
    public function test_distribuye_los_recortes_xrf_por_numero_de_disparo(): void
    {
        $this->assertSame([
            'pagina' => 1,
            'posicion' => 'arriba_izquierda',
            'una_hoja' => 0,
        ], $this->normalizar(null, null, 1000));

        $this->assertSame([
            'pagina' => 1,
            'posicion' => 'arriba_derecha',
            'una_hoja' => 0,
        ], $this->normalizar(null, null, 1001));

        $this->assertSame([
            'pagina' => 3,
            'posicion' => 'arriba_derecha',
            'una_hoja' => 0,
        ], $this->normalizar(null, null, 1005));
    }

    /** Create conserva un mismo índice para todos los datos de una fotografía manual. */
    public function test_create_no_mezcla_indices_manuales_con_recortes_xrf(): void
    {
        $scriptCreate = file_get_contents(public_path('js/Reportes_Create_IM_02.js'));
        $scriptLayout = file_get_contents(public_path('js/Reportes_Fotos_Posicionables_02_B_04.js'));

        $this->assertStringContainsString("const sufijoCampo = formId === 'FOR-PIMP-04_03'", $scriptCreate);
        $this->assertStringContainsString('name="images_base64${sufijoCampo}"', $scriptCreate);
        $this->assertStringContainsString('name="comments${sufijoCampo}"', $scriptCreate);
        $this->assertStringContainsString("indice = obtenerIndiceCampo(contenedor);", $scriptLayout);
    }

    /** Edit presenta primero lo editable y evita los identificadores duplicados reportados por Chrome. */
    public function test_edit_deja_disparos_abajo_y_las_vistas_no_duplican_input_success(): void
    {
        $create = file_get_contents(resource_path('views/Reportes/IM/Create/FOR-PIMP-04_03.blade.php'));
        $edit = file_get_contents(resource_path('views/Reportes/IM/Edit/FOR-PIMP-04_03.blade.php'));

        $this->assertStringContainsString('collect($Fotos_Comentarios)->sortBy(', $edit);
        $this->assertGreaterThan(
            strpos($edit, 'id="imageFieldsContainer"'),
            strpos($edit, 'id="recortesXrfDisparos"')
        );
        $this->assertStringNotContainsString('id="inputSuccess"', $create);
        $this->assertStringNotContainsString('id="inputSuccess"', $edit);
    }

    /** Permite formar un disparo combinando una imagen nueva y otra ya guardada. */
    public function test_acepta_dos_imagenes_manuales_para_un_disparo_sin_pdf(): void
    {
        $request = Request::create('/', 'POST', [
            'images_base64' => [0 => 'data:image/png;base64,AAAA', 1 => ''],
            'existing_images' => [1 => 'storage/foto-existente.png'],
            'es_disparo' => [0 => 1, 1 => 1],
            'numero_disparo' => [0 => 2, 1 => 2],
        ]);

        $this->validarDisparos($request);
        $this->addToAssertionCount(1);
    }

    /** Rechaza disparos incompletos para evitar espacios vacíos en el PDF. */
    public function test_rechaza_un_disparo_manual_con_una_sola_imagen(): void
    {
        $request = Request::create('/', 'POST', [
            'images_base64' => [0 => 'data:image/png;base64,AAAA'],
            'es_disparo' => [0 => 1],
            'numero_disparo' => [0 => 1],
        ]);

        $this->expectException(ValidationException::class);
        $this->validarDisparos($request);
    }

    /** Invoca la normalización privada sin exponerla como API pública del controlador. */
    private function normalizar($pagina, $posicion, int $index): array
    {
        $controller = new FOR_PIMP_04_03Controller();
        $method = new ReflectionMethod($controller, 'normalizeFotoLayout');
        $method->setAccessible(true);

        return $method->invoke($controller, $pagina, $posicion, $index);
    }

    /** Ejecuta la validación privada con solicitudes construidas específicamente para cada escenario. */
    private function validarDisparos(Request $request): void
    {
        $controller = new FOR_PIMP_04_03Controller();
        $method = new ReflectionMethod($controller, 'validarDisparosDelRequest');
        $method->setAccessible(true);
        $method->invoke($controller, $request);
    }
}
