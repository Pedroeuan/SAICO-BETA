<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\IM\FOR_PIMP_04_03Controller;
use Illuminate\Http\Request;
use Tests\TestCase;

class FORPIMP0403VinculosMaterialTest extends TestCase
{
    public function test_vincula_pieza_y_promedio_en_el_controlador(): void
    {
        $equipo = ['DESCRIPCION_MATERIAL' => 'Anterior', 'DUREZA_BRINELL' => '0'];
        $request = Request::create('/', 'POST', ['Detalles_Generales' => ['Nombre_Pieza' => 'Spool J-2'],
            'Datos_Equipo' => ['VALORES_DUREZA' => ['100', '110']]]);
        $metodo = new \ReflectionMethod(FOR_PIMP_04_03Controller::class, 'guardarPromedioDureza');
        $metodo->invokeArgs(new FOR_PIMP_04_03Controller, [$request, &$equipo]);
        $this->assertSame('Spool J-2', $equipo['DESCRIPCION_MATERIAL']);
        $this->assertSame('105', $equipo['DUREZA_BRINELL']);
        $this->assertSame($equipo['PROMEDIO_DUREZA'], $equipo['DUREZA_BRINELL']);
    }

    public function test_las_referencias_guardadas_alimentan_la_especificacion(): void
    {
        $equipo = [];
        $request = Request::create('/', 'POST', ['Modo_Referencias_0402' => 'manual',
            'Referencias_Manuales_0402' => json_encode([
                ['norma' => 'ASTM A36 / A36', 'tension_min' => '58', 'cedencia_min' => '36'],
                ['norma' => 'ASTM A572 / Grado 50', 'tension_min' => '65', 'cedencia_min' => '50'],
            ])]);
        $metodo = new \ReflectionMethod(FOR_PIMP_04_03Controller::class, 'guardarReferenciasMecanicas');
        $metodo->invokeArgs(new FOR_PIMP_04_03Controller, [$request, &$equipo]);
        $this->assertCount(2, $equipo['NORMAS_REFERENCIA_COINCIDENCIAS']);
        $this->assertSame('ASTM A36 / A36 / ASTM A572 / Grado 50', $equipo['ESPECIFICACION_MATERIAL']);
        $this->assertSame('58', (string) $equipo['NORMAS_REFERENCIA_COINCIDENCIAS'][0]['tension_min']);
    }

    public function test_respeta_la_especificacion_corregida_por_el_tecnico_incluso_vacia(): void
    {
        foreach (['ASTM A105, según evaluación del técnico', ''] as $texto) {
            $equipo = [];
            $request = Request::create('/', 'POST', ['Modo_Referencias_0402' => 'manual',
                'Datos_Equipo' => ['ESPECIFICACION_MATERIAL' => $texto],
                'Referencias_Manuales_0402' => json_encode([['norma' => 'ASTM A36', 'tension_min' => '58']])]);
            $metodo = new \ReflectionMethod(FOR_PIMP_04_03Controller::class, 'guardarReferenciasMecanicas');
            $metodo->invokeArgs(new FOR_PIMP_04_03Controller, [$request, &$equipo]);
            $this->assertSame($texto, $equipo['ESPECIFICACION_MATERIAL']);
            $this->assertSame('ASTM A36', $equipo['NORMAS_REFERENCIA_COINCIDENCIAS'][0]['norma']);
        }
    }

    public function test_un_formulario_anterior_no_descarta_la_referencia_guardada(): void
    {
        $equipo = ['NORMA_REFERENCIA' => 'ASTM A36', 'ESPECIFICACION_MATERIAL' => 'ASTM A36'];
        $metodo = new \ReflectionMethod(FOR_PIMP_04_03Controller::class, 'guardarReferenciasMecanicas');
        $metodo->invokeArgs(new FOR_PIMP_04_03Controller, [Request::create('/', 'POST'), &$equipo]);
        $this->assertSame('ASTM A36', $equipo['ESPECIFICACION_MATERIAL']);
        $this->assertSame('ASTM A36', $equipo['NORMA_REFERENCIA']);
    }
}
