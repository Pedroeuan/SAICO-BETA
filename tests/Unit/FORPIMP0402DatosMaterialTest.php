<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\IM\FOR_PIMP_04_02Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use ReflectionMethod;
use Tests\TestCase;

class FORPIMP0402DatosMaterialTest extends TestCase
{
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
