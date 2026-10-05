<?php

namespace Tests\Unit;

use App\Http\Controllers\Clientes\ClientesController;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class PortalEstadisticasTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');
        // Base aislada: estas pruebas no escriben en los datos del portal.
        foreach ([
            'CREATE TABLE Clientes (idClientes INTEGER, portal_token TEXT, Cliente TEXT, logo TEXT)',
            'CREATE TABLE orden_servicio (idOrden_Servicio INTEGER, idClientes INTEGER, Contrato TEXT, Proyecto_actividad TEXT)',
            'CREATE TABLE Reportes (idReportes INTEGER, idPrueba_Aplica INTEGER, Detalles_Generales TEXT)',
            'CREATE TABLE lineal_ideal (idOrden_Servicio INTEGER, idReportes INTEGER)',
            'CREATE TABLE Prueba_Aplica (idPrueba_Aplica INTEGER, idPrueba INTEGER, idFormato INTEGER)',
            'CREATE TABLE Formato (idFormato INTEGER, Nombre TEXT)',
            'CREATE TABLE Grupo_Juntas_Detalles_Re (idReportes INTEGER, Juntas_Grupo_Re TEXT)',
            'CREATE TABLE Prueba (idPrueba INTEGER, Nombre TEXT)',
        ] as $sql) DB::statement($sql);
        DB::table('Clientes')->insert(['idClientes' => 1, 'portal_token' => 'cliente-prueba', 'Cliente' => 'Prueba']);
        DB::table('orden_servicio')->insert([
            ['idOrden_Servicio' => 1, 'idClientes' => 1, 'Contrato' => 'A', 'Proyecto_actividad' => 'Proyecto A'],
            ['idOrden_Servicio' => 2, 'idClientes' => 1, 'Contrato' => 'B', 'Proyecto_actividad' => 'Proyecto B'],
            ['idOrden_Servicio' => 3, 'idClientes' => 1, 'Contrato' => 'C', 'Proyecto_actividad' => 'Sin reportes'],
            ['idOrden_Servicio' => 4, 'idClientes' => 2, 'Contrato' => 'AJENO', 'Proyecto_actividad' => 'Otro cliente'],
        ]);
        DB::table('Prueba')->insert(['idPrueba' => 1, 'Nombre' => 'Ultrasonido']);
        DB::table('Prueba_Aplica')->insert(['idPrueba_Aplica' => 1, 'idPrueba' => 1]);
        foreach ([1 => 'storage/firmado.pdf', 2 => null, 3 => null, 4 => null] as $id => $firma) {
            DB::table('Reportes')->insert(['idReportes' => $id, 'idPrueba_Aplica' => 1,
                'Detalles_Generales' => json_encode(['No_Reporte' => 'R-' . $id, 'Fecha' => '2026-10-01', 'Reporte_Firmado' => $firma])]);
            DB::table('lineal_ideal')->insert(['idOrden_Servicio' => $id === 1 ? 1 : ($id === 4 ? 4 : 2), 'idReportes' => $id]);
        }
        DB::table('lineal_ideal')->insert(['idOrden_Servicio' => 2, 'idReportes' => 2]);
    }

    private function consultar(string $contrato = '', string $vista = 'estadisticas', array $opciones = []): array
    {
        $request = Request::create('/portal/cliente-prueba', 'GET', ['vista' => $vista, 'contrato' => $contrato] + $opciones);
        $route = new Route('GET', 'portal/{token}', fn () => null);
        $route->bind($request);
        $request->setRouteResolver(fn () => $route);
        $this->app->instance('request', $request);
        $view = (new ClientesController)->Portal_index('cliente-prueba');
        $view->with('errors', new ViewErrorBag());
        return [$view->getData(), $view->render()];
    }

    public function test_conteos_unicos_aislamiento_y_enlace_al_reporte(): void
    {
        [$datos, $html] = $this->consultar();
        $this->assertSame(3, $datos['estadisticas']['reportes']);
        $this->assertSame(1, $datos['estadisticas']['firmados']);
        $this->assertSame(3, $datos['estadisticas']['ensayos']->sum());
        $this->assertSame(3, $datos['estadisticas']['mensuales']->sum());
        $this->assertSame(2, $datos['ordenesEstadistica']->first()->idOrden_Servicio);
        $this->assertMatchesRegularExpression('~/reportes_clientes/2\?[^" ]*#reporte-2~', $html);
        $this->assertStringNotContainsString('AJENO', $html);
    }

    public function test_contrato_totalmente_firmado(): void
    {
        [$datos, $html] = $this->consultar('A');
        $this->assertSame(1, $datos['estadisticas']['reportes']);
        $this->assertSame(1, $datos['estadisticas']['firmados']);
        $this->assertStringContainsString('Todos los reportes de este contrato están liberados.', $html);
        $this->assertStringNotContainsString('id="detalle-pendientes"', $html);
    }

    public function test_contrato_totalmente_pendiente(): void
    {
        [$datos, $html] = $this->consultar('B');
        $this->assertSame(2, $datos['estadisticas']['reportes']);
        $this->assertSame(0, $datos['estadisticas']['firmados']);
        $this->assertStringContainsString('Quedan 2 reportes por liberar en este contrato.', $html);
    }

    public function test_contrato_sin_reportes(): void
    {
        [$datos, $html] = $this->consultar('C');
        $this->assertSame(0, $datos['estadisticas']['reportes']);
        $this->assertStringContainsString('Este contrato no tiene reportes registrados.', $html);
        $this->assertSame('sin_reportes', $datos['ordenesEstadistica']->first()->estado_documentacion);
    }

    public function test_cliente_sin_contratos(): void
    {
        DB::table('orden_servicio')->where('idClientes', 1)->delete();
        [$datos, $html] = $this->consultar();
        $this->assertSame(0, $datos['estadisticas']['reportes']);
        $this->assertStringContainsString('No tienes reportes registrados', $html);
    }

    public function test_varias_ordenes_y_reporte_compartido_se_cuentan_una_vez(): void
    {
        DB::table('orden_servicio')->where('idOrden_Servicio', 3)->update(['Contrato' => 'B']);
        DB::table('lineal_ideal')->insert(['idOrden_Servicio' => 3, 'idReportes' => 2]);
        [$datos] = $this->consultar('B');
        $this->assertSame(2, $datos['estadisticas']['ordenes']);
        $this->assertSame(2, $datos['estadisticas']['reportes']);
        $this->assertSame(1, $datos['ordenesEstadistica']->firstWhere('idOrden_Servicio', 3)->total_reportes);
    }

    public function test_token_invalido_devuelve_404(): void
    {
        try {
            (new ClientesController)->Portal_index('inexistente');
            $this->fail('Debió devolver 404');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $error) {
            $this->assertSame(404, $error->getStatusCode());
        }
    }

    public function test_contratos_no_consulta_reportes(): void
    {
        DB::enableQueryLog();
        [$datos, $html] = $this->consultar('', 'contratos');
        $this->assertArrayNotHasKey('estadisticas', $datos);
        $this->assertStringContainsString('Mis contratos / Proyectos', $html);
        foreach (DB::getQueryLog() as $consulta) {
            $this->assertStringNotContainsString('lineal_ideal', $consulta['query']);
        }
    }

    public function test_busqueda_filtra_tablas_sin_cambiar_totales(): void
    {
        [$datos, $html] = $this->consultar('', 'estadisticas', ['buscar' => 'R-2']);
        $this->assertSame(3, $datos['estadisticas']['reportes']);
        $this->assertSame(1, $datos['ordenesPaginadas']->total());
        $this->assertSame(1, $datos['pendientesPaginados']->total());
        $this->assertStringContainsString('buscar=R-2', $html);
        [$datos] = $this->consultar('', 'estadisticas', ['buscar' => 'inexistente']);
        $this->assertSame(0, $datos['ordenesPaginadas']->total());
    }

    public function test_paginacion_conserva_busqueda_y_contrato(): void
    {
        for ($i = 10; $i < 22; $i++) {
            DB::table('Reportes')->insert(['idReportes' => $i, 'idPrueba_Aplica' => 1, 'Detalles_Generales' => json_encode(['No_Reporte' => 'R-' . $i])]);
            DB::table('lineal_ideal')->insert(['idOrden_Servicio' => 2, 'idReportes' => $i]);
        }
        [$datos] = $this->consultar('B', 'estadisticas', ['buscar' => 'R-', 'pagina_pendientes' => '2']);
        $this->assertSame(14, $datos['pendientesPaginados']->total());
        $this->assertCount(4, $datos['pendientesPaginados']->items());
        $this->assertStringContainsString('contrato=B', $datos['pendientesPaginados']->previousPageUrl());
        $this->assertStringContainsString('buscar=R-', $datos['pendientesPaginados']->previousPageUrl());
    }


}
