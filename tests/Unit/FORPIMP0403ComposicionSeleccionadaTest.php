<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\IM\FOR_PIMP_04_03Controller;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FORPIMP0403ComposicionSeleccionadaTest extends TestCase
{
    public function test_guarda_las_composiciones_de_dos_normas_y_sus_colores_en_lugar_de_solo_la_primera(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('Normas_IM', function (Blueprint $tabla) {
            $tabla->integer('idnormas_im')->primary(); $tabla->string('Nombre_Espe');
            $tabla->string('Variable')->nullable(); $tabla->text('Tabla');
            $tabla->text('Observaciones')->nullable(); $tabla->text('Tablas_Adicionales')->nullable();
        });
        DB::table('Normas_IM')->insert(['idnormas_im' => 1, 'Nombre_Espe' => 'ASTM A36', 'Tabla' => '[]']);
        $referencias = [
            ['idnormas_im' => 1, 'norma' => 'ASTM A36', 'composicion_seleccionada' => ['Tabla' => [['Elemento' => 'C', 'Composicion' => '0.30 máx.']]]],
            ['idnormas_im' => 2, 'norma' => 'ASTM A572', 'composicion_seleccionada' => ['Tabla' => [
                ['Elemento' => 'C', 'Composicion' => '0.25 máx.'], ['Elemento' => 'Mn', 'Composicion' => '1.60 máx.'],
            ]]],
        ];
        $request = Request::create('/', 'POST', ['Norma_IM' => ['idnormas_im' => 1, 'Promedio' => ['ND', '0.5217']]]);
        $request->attributes->set('referencias_quimicas_0402', $referencias);
        $metodo = new \ReflectionMethod(FOR_PIMP_04_03Controller::class, 'construirNormaIM');
        $resultado = $metodo->invoke(new FOR_PIMP_04_03Controller, $request);
        $this->assertSame(['C', 'Mn'], array_column($resultado['Tabla'], 'Elemento'));
        $this->assertSame('ND', $resultado['Tabla'][0]['Promedio']);
        $this->assertSame('0.5217', $resultado['Tabla'][1]['Promedio']);
        $this->assertCount(2, $resultado['Tabla'][0]['Referencias']);
        $this->assertSame(1, $resultado['Tabla'][1]['Referencias'][0]['indice']);
        $html = view('Reportes.IM.partials.limites-quimicos-0403-pdf', ['filaNorma' => $resultado['Tabla'][0]])->render();
        $this->assertStringContainsString('#111111', $html);
        $this->assertStringContainsString('#1565c0', $html);
        $this->assertStringContainsString('0.30 máx.', $html);
        $this->assertStringContainsString('0.25 máx.', $html);
    }
}
