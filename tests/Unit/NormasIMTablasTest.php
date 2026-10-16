<?php

namespace Tests\Unit;

use App\Http\Controllers\Normas_IM\NormasIMController;
use App\Models\Normas_IM\Normas_IM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class NormasIMTablasTest extends NormasIMAltaRapidaTest
{
    protected function setUp(): void
    {
        parent::setUp();
        (require database_path('migrations/2026_10_13_000003_add_tablas_adicionales_to_normas_im.php'))->up();
        view()->share('errors', new \Illuminate\Support\ViewErrorBag);
    }

    private function formulario(): array
    {
        return ['NombreESP' => 'ASTM A182', 'Variable' => 'F 316L', 'Elemento' => ['C', 'Cr'], 'Composicion' => ['0.030 máx.', '16.0–18.0']];
    }

    public function test_guarda_tension_separada_y_la_transmite_al_catalogo_del_0402(): void
    {
        (new NormasIMController)->store(Request::create('/', 'POST', $this->formulario() + ['Tablas_Adicionales' => [
            'mecanica' => [['grado' => 'F 316L', 'tension_min' => '58 ksi', 'tension_max' => '80 ksi']],
        ]]));
        $norma = Normas_IM::first();
        $this->assertSame('58 ksi', $norma->Tablas_Adicionales['mecanica'][0]['tension_min']);
        $this->assertSame('80 ksi', $norma->Tablas_Adicionales['mecanica'][0]['tension_max']);
        $fila = (new \App\Services\CoincidenciasMecanicas0402)->catalogo([$norma])[0];
        $this->assertEquals(58, $fila['tension_min']);
        $this->assertEquals(80, $fila['tension_max']);
        $html = (new NormasIMController)->edit($norma->idnormas_im)->render();
        $this->assertStringContainsString('Resistencia a la tensión mínima', $html);
        $this->assertStringContainsString('Resistencia a la tensión máxima', $html);
    }

    public function test_separa_intervalos_anteriores_con_ambos_sistemas_sin_cambiar_unidades(): void
    {
        $fila = \App\Services\TablasNormasIM::separarTension(['tension' => '58–80 [400–550]']);
        $this->assertSame('58 [400]', $fila['tension_min']);
        $this->assertSame('80 [550]', $fila['tension_max']);
        $this->assertSame('', \App\Services\TablasNormasIM::separarTension(['tension' => '70 [485]'])['tension_max']);
        $fila = \App\Services\TablasNormasIM::separarTension(['tension' => '58–80 ksi', 'tension_min' => '', 'tension_max' => '']);
        $this->assertSame('', $fila['tension_min']);
        $this->assertSame('', $fila['tension_max']);
    }

    public function test_guarda_y_carga_las_tablas_disponibles_en_la_misma_norma(): void
    {
        $datos = $this->formulario() + ['Tablas_Adicionales' => [
            'tratamiento' => [['grado' => 'F 316L', 'tratamiento' => 'Solubilizado', 'temperatura' => '1900 [1040]', 'enfriamiento' => 'Líquido'], []],
            'mecanica' => [['grado' => 'F 316L', 'tension' => '70 [485]', 'fluencia' => '25 [170]', 'dureza' => '...']],
        ]];
        (new NormasIMController)->store(Request::create('/', 'POST', $datos));
        $norma = Normas_IM::first();
        $this->assertCount(2, json_decode($norma->Tabla, true));
        $this->assertCount(1, $norma->Tablas_Adicionales['tratamiento']);
        $this->assertSame('70 [485]', $norma->Tablas_Adicionales['mecanica'][0]['tension']);
        $html = (new NormasIMController)->edit($norma->idnormas_im)->render();
        foreach (['Tratamiento térmico', 'Composición química', 'Propiedades mecánicas y dureza', '1900 [1040]'] as $valor) {
            $this->assertStringContainsString($valor, $html);
        }
        $this->assertSame(1, DB::table('Normas_IM')->count());
    }

    public function test_actualizacion_antigua_conserva_tablas_y_se_puede_vaciar_una_pestana(): void
    {
        $controlador = new NormasIMController;
        $controlador->store(Request::create('/', 'POST', $this->formulario() + ['Tablas_Adicionales' => ['mecanica' => [['grado' => 'F 316L', 'tension' => '70 [485]']]]]));
        $id = Normas_IM::first()->idnormas_im;
        $controlador->update(Request::create('/', 'POST', $this->formulario()), $id);
        $this->assertCount(1, Normas_IM::find($id)->Tablas_Adicionales['mecanica']);
        $controlador->update(Request::create('/', 'POST', $this->formulario() + ['Tablas_Adicionales' => ['mecanica' => '']]), $id);
        $this->assertSame([], Normas_IM::find($id)->Tablas_Adicionales['mecanica']);
        $this->assertCount(2, json_decode(Normas_IM::find($id)->Tabla, true));
    }

    public function test_datos_invalidos_no_guardan_parcialmente_la_norma(): void
    {
        try {
            (new NormasIMController)->store(Request::create('/', 'POST', $this->formulario() + ['Tablas_Adicionales' => ['mecanica' => [['tension' => ['invalido']]]]]));
            $this->fail('Debe rechazar campos que no son texto.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('Tablas_Adicionales.mecanica.0.tension', $e->errors());
            $this->assertSame(0, Normas_IM::count());
        }
    }

    public function test_guarda_tablas_personalizadas_de_otra_norma(): void
    {
        $datos = $this->formulario();
        $datos['NombreESP'] = 'ISO — norma de ejemplo';
        $datos['Tablas_Adicionales'] = ['otras' => [[
            'titulo' => 'Ensayo de impacto', 'columnas' => ['Temperatura', 'Energía mínima', 'Unidad'],
            'filas' => [['-20', '27', 'J']],
        ]]];
        (new NormasIMController)->store(Request::create('/', 'POST', $datos));
        $tabla = Normas_IM::first()->Tablas_Adicionales['otras'][0];
        $this->assertSame('Ensayo de impacto', $tabla['titulo']);
        $this->assertSame(['-20', '27', 'J'], $tabla['filas'][0]);
    }

    public function test_separa_limites_del_mismo_elemento_por_producto_y_espesor(): void
    {
        $datos = [
            'NombreESP' => 'ASTM A36/A36M-19', 'Variable' => 'A36',
            'Elemento' => ['Mn', 'Mn'], 'Composicion' => ['Sin límite especificado', '0.80–1.20 %'],
            'Contexto' => [
                ['producto' => 'Placa', 'ancho' => '>380 mm', 'espesor' => '≤20 mm'],
                ['producto' => 'Placa', 'ancho' => '>380 mm', 'espesor' => '>20–40 mm', 'condiciones' => 'Nota B'],
            ],
            'Tablas_Adicionales' => ['mecanica' => [['grado' => 'A36', 'producto' => 'Placa', 'espesor' => '>200 mm', 'fluencia' => '220 MPa']]],
        ];
        $controlador = new NormasIMController;
        $controlador->store(Request::create('/', 'POST', $datos));
        $norma = Normas_IM::first();
        $filas = json_decode($norma->Tabla, true);
        $this->assertSame('≤20 mm', $filas[0]['Aplicabilidad']['espesor']);
        $this->assertSame('>20–40 mm', $filas[1]['Aplicabilidad']['espesor']);
        $this->assertSame('>200 mm', $norma->Tablas_Adicionales['mecanica'][0]['espesor']);
        $this->assertStringNotContainsString('Contexto[1][espesor]', $controlador->edit($norma->idnormas_im)->render());
        unset($datos['Contexto'], $datos['Tablas_Adicionales']);
        $controlador->update(Request::create('/', 'POST', $datos), $norma->idnormas_im);
        $this->assertSame($filas[0]['Aplicabilidad'], json_decode($norma->fresh()->Tabla, true)[0]['Aplicabilidad']);
    }

    public function test_guarda_configuraciones_quimicas_con_limites_y_condiciones_sin_cambiar_la_tabla_anterior(): void
    {
        $datos = $this->formulario();
        $datos['Quimica_Configuraciones'] = json_encode([
            ['producto' => 'Placas', 'ancho' => '>380 mm', 'espesor' => '≤20 mm', 'elementos' => [
                ['elemento' => 'C', 'minimo' => null, 'maximo' => 0.25],
                ['elemento' => 'Mn', 'minimo' => null, 'maximo' => null],
                ['elemento' => 'Cu', 'minimo' => 0.20, 'maximo' => null, 'condicion' => 'Cuando se especifica acero al cobre'],
            ]],
            ['producto' => 'Placas', 'ancho' => '>380 mm', 'espesor' => '>20 y ≤40 mm', 'elementos' => [
                ['elemento' => 'Mn', 'minimo' => 0.80, 'maximo' => 1.20],
            ]],
        ]);
        (new NormasIMController)->store(Request::create('/', 'POST', $datos));
        $norma = Normas_IM::first();
        $casos = $norma->Tablas_Adicionales['quimica_configuraciones'];
        $this->assertCount(2, $casos);
        $this->assertNull($casos[0]['elementos'][1]['minimo']);
        $this->assertEquals(1.20, $casos[1]['elementos'][0]['maximo']);
        $this->assertSame('Cuando se especifica acero al cobre', $casos[0]['elementos'][2]['condicion']);
        $this->assertCount(2, json_decode($norma->Tabla, true));
        $this->assertStringContainsString('Consulta de valores registrados', (new NormasIMController)->edit($norma->idnormas_im)->render());
    }

    public function test_rechaza_configuraciones_repetidas_o_limites_invertidos_sin_guardar(): void
    {
        $config = ['producto' => 'Placas', 'espesor' => '≤20 mm', 'elementos' => [['elemento' => 'C', 'minimo' => 1, 'maximo' => 0.25]]];
        foreach ([[$config], [array_replace($config, ['elementos' => [['elemento' => 'C', 'maximo' => 0.25]]]), array_replace($config, ['elementos' => [['elemento' => 'C', 'maximo' => 0.25]]])]] as $casos) {
            try {
                (new NormasIMController)->store(Request::create('/', 'POST', $this->formulario() + ['Quimica_Configuraciones' => json_encode($casos)]));
                $this->fail('Debe rechazar la configuración inválida.');
            } catch (ValidationException $e) {
                $this->assertArrayHasKey('Quimica_Configuraciones', $e->errors());
                $this->assertSame(0, Normas_IM::count());
            }
        }
    }

    public function test_crea_norma_con_una_tabla_sin_validar_las_otras_pestanas(): void
    {
        (new NormasIMController)->store(Request::create('/', 'POST', [
            'NombreESP' => 'Norma de prueba', 'guardar_seccion' => 'mecanica',
            'Quimica_Configuraciones' => '{invalido',
            'Tablas_Adicionales' => [
                'mecanica' => [['grado' => 'A', 'tension' => '400–550 MPa']],
                'otras' => [['titulo' => '', 'columnas' => []]],
            ],
        ]));
        $norma = Normas_IM::first();
        $this->assertSame([], json_decode($norma->Tabla, true));
        $this->assertSame('400–550 MPa', $norma->Tablas_Adicionales['mecanica'][0]['tension']);
        $this->assertArrayNotHasKey('otras', $norma->Tablas_Adicionales);
    }

    public function test_vincula_el_grado_elegido_a_la_tabla_usada_por_formatos(): void
    {
        $datos = $this->formulario();
        $datos['guardar_seccion'] = 'quimica';
        $datos['Quimica_Para_Formatos'] = '1';
        $datos['Quimica_Configuraciones'] = json_encode([
            ['grado' => 'Grado A', 'elementos' => [['elemento' => 'C', 'maximo' => 0.17]]],
            ['grado' => 'Grado B', 'elementos' => [['elemento' => 'C', 'maximo' => 0.22]]],
        ]);
        (new NormasIMController)->store(Request::create('/', 'POST', $datos));
        $norma = Normas_IM::first();
        $seleccion = \App\Services\SeleccionComposicionIM::resolver($norma, Request::create('/', 'POST', ['Norma_IM' => ['configuracion' => 1]]));
        $this->assertSame('Grado B', $seleccion['Variable']);
        $this->assertSame('F 316L', $norma->Variable);
        $fila = $seleccion['Tabla'][0];
        $this->assertSame('0.22 % máximo', $fila['Composicion']);
        $this->assertSame('Grado B', $fila['Aplicabilidad']['grado']);
        $this->assertCount(1, $seleccion['Tabla']);
    }

    public function test_guardar_tratamiento_conserva_composicion_y_otras_tablas(): void
    {
        $controlador = new NormasIMController;
        $controlador->store(Request::create('/', 'POST', $this->formulario() + ['Tablas_Adicionales' => ['mecanica' => [['tension' => '485 MPa']]]]));
        $norma = Normas_IM::first();
        $tablaAnterior = $norma->Tabla;
        $datos = $this->formulario();
        $datos['guardar_seccion'] = 'tratamiento';
        $datos['Elemento'] = ['Cambios ajenos a la pestaña'];
        $datos['Tablas_Adicionales'] = ['tratamiento' => [['temperatura' => '1040 C']], 'mecanica' => []];
        $controlador->update(Request::create('/', 'POST', $datos), $norma->idnormas_im);
        $norma->refresh();
        $this->assertSame($tablaAnterior, $norma->Tabla);
        $this->assertSame('485 MPa', $norma->Tablas_Adicionales['mecanica'][0]['tension']);
        $this->assertSame('1040 C', $norma->Tablas_Adicionales['tratamiento'][0]['temperatura']);
    }

    public function test_resuelve_intervalos_quimicos_sin_error_de_interpolacion(): void
    {
        $norma = new Normas_IM;
        $norma->Tablas_Adicionales = ['quimica_configuraciones' => [[
            'grado' => 'A36', 'elementos' => [['elemento' => 'Mn', 'minimo' => 0.80, 'maximo' => 1.20]],
        ]]];
        $opciones = \App\Services\SeleccionComposicionIM::opciones($norma);
        $this->assertSame('0.8–1.2 %', $opciones[0]['Tabla'][0]['Composicion']);
    }
}
