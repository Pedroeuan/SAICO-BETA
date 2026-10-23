<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\IM\FOR_PIMP_02_B_03Controller;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FORPIMP0203TecnicoFirmaTest extends TestCase
{
    public function test_nombre_se_resuelve_del_id_y_llega_a_las_cuatro_variantes_del_pdf(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $tabla) {
            $tabla->integer('id')->primary(); $tabla->string('name');
        });
        DB::table('users')->insert(['id' => 7, 'name' => 'Técnico seleccionado']);
        $metodo = new \ReflectionMethod(FOR_PIMP_02_B_03Controller::class, 'completarTecnicoFirma');
        $metodo->setAccessible(true);
        foreach (range(1, 4) as $numero) {
            $clave = 'Firmas_Reportes' . $numero;
            $datos = [$clave => ['ID_TECNICO' => '7', 'NOMBRE_TECNICO' => '', 'CARGO_TECNICO' => 'Inspector']];
            $metodo->invokeArgs(new FOR_PIMP_02_B_03Controller, [&$datos, $numero]);
            $this->assertSame('Técnico seleccionado', $datos[$clave]['NOMBRE_TECNICO']);
            $this->assertSame('Inspector', $datos[$clave]['CARGO_TECNICO']);
            $firmaGuardada = json_decode(json_encode($datos[$clave]), true);
            $html = view('Reportes.partials.firmas_im_pdf', ['numFirmas' => $numero, 'Firmas_Reportes' => $firmaGuardada])->render();
            $this->assertStringContainsString('Técnico seleccionado', $html);
        }
    }
}
