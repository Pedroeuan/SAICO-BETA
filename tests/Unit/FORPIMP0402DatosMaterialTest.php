<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\IM\FOR_PIMP_04_02Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use ReflectionMethod;
use Tests\TestCase;

class FORPIMP0402DatosMaterialTest extends TestCase
{
    public function test_tamano_del_material_usa_el_mismo_valor_del_patron_historico(): void
    {
        $servicio = new \App\Services\ServicioPatronGranoReporte;
        $this->assertSame('7.0', $servicio->tamanoParaMaterial(['descripcion' => 'TAMAÑO DE GRANO 7.0 COMPARATIVA ASTM E-112.']));
        $this->assertSame('6.5', $servicio->tamanoParaMaterial(['valor_grano' => '6,5']));
        $this->assertSame('8', $servicio->tamanoParaMaterial(['nombre' => 'Grano 8']));
    }

    public function test_a370_redondea_el_promedio_y_recalcula_ambas_resistencias_en_servidor(): void
    {
        $equipo = ['RESISTENCIA_TENSION' => '999', 'RESISTENCIA_CEDENCIA' => '999'];
        $this->calcular(['Datos_Equipo' => ['METODO_CALCULO_DUREZA' => 'a370_tabla3',
            'ESCALA_DUREZA' => 'HB', 'VALORES_DUREZA' => ['160', '167', '163', '165', '170', '167', '164', '162', '164', '166']]], $equipo);
        $this->assertSame('165', $equipo['PROMEDIO_DUREZA']);
        $this->assertSame('82.000', $equipo['RESISTENCIA_TENSION']);
        $this->assertSame('55.671', $equipo['RESISTENCIA_CEDENCIA']);
        $this->assertSame('tabla', $equipo['CALCULO_DUREZA']['estado']);
        $this->assertSame('ASTM A370-20', $equipo['CALCULO_DUREZA']['norma_tension']);
    }

    public function test_a370_interpola_y_respeta_los_valores_publicados(): void
    {
        $servicio = new \App\Services\ReferenciasMecanicasIM;
        foreach (\App\Services\ReferenciasMecanicasIM::A370_TABLA3 as $hb => $ksi) {
            $this->assertSame(number_format($ksi, 3, '.', ''), $servicio->calcularDesdeBrinell($hb)['tension']);
        }
        $this->assertSame('70.000', $servicio->calcularDesdeBrinell(147)['tension']);
        $this->assertSame('91.200', $servicio->calcularDesdeBrinell(193)['tension']);
        $this->assertSame('64.333', $servicio->calcularDesdeBrinell(133)['tension']);
        $this->assertSame('86.000', $servicio->calcularDesdeBrinell(176)['tension']);
        $this->assertSame('interpolacion', $servicio->calcularDesdeBrinell(193)['estado']);
        $this->assertSame([[190, 90], [195, 92]], $servicio->calcularDesdeBrinell(193)['puntos']);
        $this->assertSame('82.000', $servicio->calcularDesdeBrinell(164.8)['tension']);
    }

    public function test_a370_no_extrapola_ni_conserva_resultados_obsoletos_al_quitar_lecturas(): void
    {
        foreach ([['99'], ['241'], [], array_fill(0, 10, '---')] as $lecturas) {
            $equipo = ['RESISTENCIA_TENSION' => '82.000', 'RESISTENCIA_CEDENCIA' => '55.671'];
            $this->calcular(['Datos_Equipo' => ['METODO_CALCULO_DUREZA' => 'a370_tabla3', 'VALORES_DUREZA' => $lecturas]], $equipo);
            $this->assertSame('---', $equipo['RESISTENCIA_TENSION']);
            $this->assertSame('---', $equipo['RESISTENCIA_CEDENCIA']);
            $this->assertContains($equipo['CALCULO_DUREZA']['estado'], ['fuera_rango', 'sin_lecturas']);
        }
    }

    public function test_100_a_115_hb_usa_formula_tecnica_en_psi_convertida_a_ksi(): void
    {
        foreach ([100 => '50.000', 110 => '55.000', 115 => '57.500', 116 => '56.000'] as $hb => $esperado) {
            $equipo = [];
            $this->calcular(['Datos_Equipo' => ['METODO_CALCULO_DUREZA' => 'a370_tabla3', 'VALORES_DUREZA' => [(string) $hb]]], $equipo);
            $this->assertSame($esperado, $equipo['RESISTENCIA_TENSION']);
            $this->assertSame($hb <= 115 ? 'formula_tecnico' : 'tabla', $equipo['CALCULO_DUREZA']['estado']);
            $this->assertSame($hb <= 115 ? null : 'ASTM A370-20', $equipo['CALCULO_DUREZA']['norma_tension']);
            $this->assertSame(number_format((-13154.92 + 417.1285 * $hb) / 1000, 3, '.', ''), $equipo['RESISTENCIA_CEDENCIA']);
        }
    }

    public function test_blade_comparte_tabla_a370_y_preserva_modo_manual_en_reportes_anteriores(): void
    {
        foreach ([[], ['RESISTENCIA_TENSION' => '82.000']] as $datos) {
            $html = view('Reportes.IM.partials.referencias-mecanicas-0402', ['Datos_Equipo' => $datos,
                'referenciasFormId' => 'FOR-PIMP-04_02'])->render();
            preg_match('/<script[^>]*data-conversion-a370[^>]*>(.*?)<\/script>/s', $html, $m);
            $config = json_decode($m[1], true, 512, JSON_THROW_ON_ERROR);
            $this->assertSame(82, $config['tabla'][165]);
            $this->assertSame($datos ? 'manual' : 'a370_tabla3', $config['metodo']);
        }
    }

    public function test_el_cv_del_qr_corresponde_al_tecnico_del_bloque_de_firmas_activo(): void
    {
        $metodo = new ReflectionMethod(FOR_PIMP_04_02Controller::class, 'tecnicoQrSeleccionado');
        foreach ([1, 2, 3, 4] as $numero) {
            $request = Request::create('/', 'POST', [
                'numFirmas' => $numero,
                'Firmas_Reportes1' => ['ID_TECNICO' => '11'],
                'Firmas_Reportes2' => ['ID_TECNICO' => '22'],
                'Firmas_Reportes3' => ['ID_TECNICO' => '33'],
                'Firmas_Reportes4' => ['ID_TECNICO' => '44'],
            ]);
            $this->assertSame((string) ($numero * 11), $metodo->invoke(new FOR_PIMP_04_02Controller, $request));
        }
        $request = Request::create('/', 'POST', ['numFirmas' => 2, 'Firmas_Reportes1' => ['ID_TECNICO' => '11'], 'Firmas_Reportes2' => ['ID_TECNICO' => '']]);
        $this->assertNull($metodo->invoke(new FOR_PIMP_04_02Controller, $request));
    }

    private function calcular(array $datos, array &$equipo): void
    {
        $metodo = new ReflectionMethod(FOR_PIMP_04_02Controller::class, 'guardarPromedioDureza');
        $request = Request::create('/', 'POST', $datos);
        $metodo->invokeArgs(new FOR_PIMP_04_02Controller, [$request, &$equipo]);
    }

    public function test_vincula_nombre_y_promedio_sin_calcular_tension_o_cedencia(): void
    {
        $equipo = ['DESCRIPCION_MATERIAL' => 'Anterior', 'DUREZA_BRINELL' => '999', 'RESISTENCIA_TENSION' => '61.5', 'RESISTENCIA_CEDENCIA' => '39.403'];
        $this->calcular([
            'Detalles_Generales' => ['Nombre_Pieza' => 'Placa de asentamiento'],
            'Datos_Equipo' => ['ESCALA_DUREZA' => 'HBW', 'VALORES_DUREZA' => ['126', '126', '126', '124', '124', '125', '126', '127', '125', '127']],
        ], $equipo);
        $this->assertSame('Placa de asentamiento', $equipo['DESCRIPCION_MATERIAL']);
        $this->assertSame('126', $equipo['PROMEDIO_DUREZA']);
        $this->assertSame('126', $equipo['DUREZA_BRINELL']);
        $this->assertSame('61.5', $equipo['RESISTENCIA_TENSION']);
        $this->assertSame('39.403', $equipo['RESISTENCIA_CEDENCIA']);
    }

    public function test_sin_mediciones_no_genera_dureza_cero(): void
    {
        $equipo = ['DUREZA_BRINELL' => '999'];
        $this->calcular(['Datos_Equipo' => ['VALORES_DUREZA' => []]], $equipo);
        $this->assertSame('', $equipo['DUREZA_BRINELL']);
        $this->assertSame('HBW', $equipo['ESCALA_DUREZA']);
    }

    public function test_no_confunde_otras_escalas_con_brinell(): void
    {
        $this->expectException(ValidationException::class);
        $equipo = [];
        $this->calcular(['Datos_Equipo' => ['ESCALA_DUREZA' => 'HRC', 'VALORES_DUREZA' => ['40']]], $equipo);
    }

    public function test_guarda_varias_referencias_manuales_sin_consultar_el_catalogo(): void
    {
        $equipo = ['NORMA_REFERENCIA' => 'Anterior'];
        $request = Request::create('/', 'POST', [
            'Modo_Referencias_0402' => 'manual',
            'Referencias_Manuales_0402' => json_encode([
                ['norma' => 'ASTM A36', 'tension_min' => '58', 'cedencia_min' => '36', 'tension_max' => '80', 'dureza_max' => ''],
                ['norma' => 'ASTM A285 Grado A', 'tension_min' => '45', 'cedencia_min' => '24', 'tension_max' => '65'],
            ]),
        ]);
        $metodo = new ReflectionMethod(FOR_PIMP_04_02Controller::class, 'guardarReferenciasMecanicas');
        $metodo->invokeArgs(new FOR_PIMP_04_02Controller, [$request, &$equipo]);
        $this->assertSame('manual', $equipo['MODO_REFERENCIAS_0402']);
        $this->assertCount(2, $equipo['NORMAS_REFERENCIA_COINCIDENCIAS']);
        $this->assertSame('ASTM A285 Grado A', $equipo['NORMAS_REFERENCIA_COINCIDENCIAS'][1]['norma']);
        $this->assertNull($equipo['NORMAS_REFERENCIA_COINCIDENCIAS'][0]['dureza_max']);
        $this->assertSame('', $equipo['NORMA_REFERENCIA']);
    }

    public function test_rechaza_limites_manuales_invertidos(): void
    {
        $this->expectException(ValidationException::class);
        (new \App\Services\ReferenciasManuales0402)->validar(json_encode([['norma' => 'Prueba', 'tension_min' => 80, 'tension_max' => 58]]));
    }

    public function test_acepta_coma_decimal_y_omite_filas_manuales_vacias(): void
    {
        $filas = (new \App\Services\ReferenciasManuales0402)->validar(json_encode([
            ['norma' => '', 'tension_min' => '', 'tension_max' => ''],
            ['norma' => 'Referencia técnica', 'tension_min' => '58,5'],
        ]));
        $this->assertCount(1, $filas);
        $this->assertSame('58.5', $filas[0]['tension_min']);
    }

    public function test_acepta_guiones_y_no_aplica_como_limites_no_establecidos(): void
    {
        $filas = (new \App\Services\ReferenciasManuales0402)->validar(json_encode([
            ['norma' => 'ASTM A36', 'dureza_max' => '---', 'tension_min' => '58', 'cedencia_min' => '36', 'tension_max' => 'N/A'],
        ]));
        $this->assertNull($filas[0]['dureza_max']);
        $this->assertNull($filas[0]['tension_max']);
        $this->assertSame('58', $filas[0]['tension_min']);
    }
}
