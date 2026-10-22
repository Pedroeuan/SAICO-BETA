<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\IM\FOR_PIMP_05_B_01Controller;
use App\Services\ReferenciasQuimicas0501;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReferenciasQuimicas0501Test extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('Normas_IM', function (Blueprint $tabla) {
            $tabla->integer('idnormas_im')->primary(); $tabla->string('Nombre_Espe');
            $tabla->string('Variable')->nullable(); $tabla->text('Tabla');
            $tabla->text('Observaciones')->nullable(); $tabla->text('Tablas_Adicionales')->nullable();
        });
        foreach ([1 => [['Elemento' => 'C', 'Composicion' => '0.30']], 2 => [['Elemento' => 'C', 'Composicion' => '0.25'], ['Elemento' => 'Mn', 'Composicion' => '1.60']], 3 => []] as $id => $filas) {
            DB::table('Normas_IM')->insert(['idnormas_im' => $id, 'Nombre_Espe' => 'Norma ' . $id, 'Tabla' => json_encode($filas)]);
        }
    }

    public function test_varias_normas_quimicas_unen_elementos_y_conservan_la_captura_al_cambiar_la_primera(): void
    {
        $this->assertCount(2, ReferenciasQuimicas0501::catalogo(\App\Models\Normas_IM\Normas_IM::all()));
        $request = Request::create('/', 'POST', ['Norma_IM' => ['seleccion_multiple' => '1', 'Seleccion_Quimica' => ['2:base', '1:base'], 'Promedio' => ['0.1234', '0.5217']]]);
        $historica = ['idnormas_im' => 1, 'Tabla' => [['Elemento' => 'C', 'Composicion' => '0.30']], 'Analisis_PDF' => [['archivo' => 'mediciones.pdf']], 'Captura_XRF' => ['ruta' => 'storage/captura.png'], 'Columnas_Seleccionadas' => [1, 2, 3]];
        $metodo = new \ReflectionMethod(FOR_PIMP_05_B_01Controller::class, 'construirNormaIM');
        $resultado = $metodo->invoke(new FOR_PIMP_05_B_01Controller, $request, $historica);
        $this->assertSame(['C', 'Mn'], array_column($resultado['Tabla'], 'Elemento'));
        $this->assertSame(['2:base', '1:base'], $resultado['Seleccion_Quimica']);
        $this->assertSame('0.1234', $resultado['Tabla'][0]['Promedio']);
        $this->assertSame($historica['Captura_XRF'], $resultado['Captura_XRF']);
        $this->assertSame($historica['Analisis_PDF'], $resultado['Analisis_PDF']);
        $html = view('Reportes.IM.partials.limites-quimicos-0403-pdf', ['filaNorma' => $resultado['Tabla'][0]])->render();
        $this->assertStringContainsString('#111111', $html);
        $this->assertStringContainsString('#1565c0', $html);
    }

    public function test_reporte_antiguo_conserva_su_tabla_aunque_el_catalogo_cambie(): void
    {
        $historica = ['idnormas_im' => 1, 'Nombre_Espe' => 'Norma histórica', 'Tabla' => [['Elemento' => 'Cu', 'Composicion' => '0.40']]];
        $seleccion = ReferenciasQuimicas0501::resolver(['1:base'], $historica);
        $this->assertSame('Cu', $seleccion[0]['composicion_seleccionada']['Tabla'][0]['Elemento']);
    }

    public function test_rechaza_referencias_ajenas_al_catalogo(): void
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        ReferenciasQuimicas0501::resolver(['999:base'], null);
    }

    public function test_06_reutiliza_varias_normas_con_limites_colores_y_pdf_historico(): void
    {
        $request = Request::create('/', 'POST', ['Norma_IM' => ['seleccion_multiple' => '1',
            'Seleccion_Quimica' => ['1:base', '2:base'], 'Promedio' => ['0.1234', '0.5217']]]);
        $historica = ['idnormas_im' => 2, 'Tabla' => [['Elemento' => 'C', 'Composicion' => '0.25'], ['Elemento' => 'Mn', 'Composicion' => '1.60']],
            'Analisis_PDF' => [['archivo' => 'disparo-guardado.pdf']]];
        $metodo = new \ReflectionMethod(\App\Http\Controllers\Reporte\IM\FOR_PIMP_06_B_01Controller::class, 'construirNormaIM');
        $metodo->setAccessible(true);
        $resultado = $metodo->invoke(new \App\Http\Controllers\Reporte\IM\FOR_PIMP_06_B_01Controller, $request, $historica);
        $this->assertSame(['1:base', '2:base'], $resultado['Seleccion_Quimica']);
        $this->assertSame(['C', 'Mn'], array_column($resultado['Tabla'], 'Elemento'));
        $this->assertSame('0.1234', $resultado['Tabla'][0]['Promedio']);
        $this->assertSame($historica['Analisis_PDF'], $resultado['Analisis_PDF']);
        $html = view('Reportes.IM.partials.limites-quimicos-0403-pdf', ['filaNorma' => $resultado['Tabla'][0]])->render();
        $this->assertStringContainsString('#111111', $html);
        $this->assertStringContainsString('#1565c0', $html);
        $this->assertStringContainsString('0.30', $html);
        $this->assertStringContainsString('0.25', $html);
        $pdfHtml = view('Reportes.ReportesPDFIM.Reporte_FOR_PIMP_06_B_01_PDF', [
            'NormaIM' => $resultado, 'Detalles_Generales' => [], 'Datos_Equipo' => [],
            'Logo' => public_path('images/Logo_AICO_R.jpg'), 'QR_PDF' => null,
            'Firmas_Reportes' => [], 'numFirmas' => 1, 'Fotos' => [], 'Disparos' => [],
            'Grupo_Juntas_Detalles_Re' => [], 'totalFotos' => 0, 'totalTitulosYFilas' => 0,
        ])->render();
        $this->assertStringContainsString('color: #111111', $pdfHtml);
        $this->assertStringContainsString('color: #1565c0', $pdfHtml);
    }

    public function test_editar_recupera_desde_juntas_las_referencias_promedios_y_captura(): void
    {
        $norma = [
            'idnormas_im' => 1, 'Seleccion_Quimica' => ['1:base', '2:base'],
            'Referencias_Quimicas' => ReferenciasQuimicas0501::resolver(['1:base', '2:base'], null),
            'Tabla' => [['Elemento' => 'C', 'Promedio' => '0.1234']],
            'Captura_XRF' => ['ruta' => 'storage/captura-guardada.png'],
        ];
        $servicio = app(\App\Services\ServicioJuntasReporteIM::class);
        $guardado = $servicio->armar([], $norma, null);
        $restaurado = $servicio->normalizar($guardado, []);
        $this->assertSame($norma, $restaurado['Norma_IM']);
        $html = view('Reportes.IM.partials.referencias-quimicas-0501', [
            'Detalles_Generales' => ['Norma_IM' => $restaurado['Norma_IM']],
            'errors' => new \Illuminate\Support\ViewErrorBag(),
        ])->render();
        preg_match('/data-quimica-config>(.*?)<\/script>/s', $html, $coincidencias);
        $configuracion = json_decode($coincidencias[1], true);
        $this->assertSame(['1:base', '2:base'], $configuracion['seleccion']);
        $request = Request::create('/', 'POST', ['Norma_IM' => ['seleccion_multiple' => '1',
            'Seleccion_Quimica' => $configuracion['seleccion'], 'Promedio' => ['0.1234', '0.5217']]]);
        $metodo = new \ReflectionMethod(FOR_PIMP_05_B_01Controller::class, 'construirNormaIM');
        $actualizada = $metodo->invoke(new FOR_PIMP_05_B_01Controller, $request, $restaurado['Norma_IM']);
        $this->assertSame($norma['Seleccion_Quimica'], $actualizada['Seleccion_Quimica']);
        $this->assertSame($norma['Captura_XRF'], $actualizada['Captura_XRF']);
        $this->assertSame('0.1234', $actualizada['Tabla'][0]['Promedio']);
    }
}
