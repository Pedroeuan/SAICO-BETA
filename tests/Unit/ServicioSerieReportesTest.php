<?php

namespace Tests\Unit;

use App\Models\Reporte\reporte;
use App\Services\ServicioSerieReportes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Tests\TestCase;

class ServicioSerieReportesTest extends TestCase
{
    public function test_0204_solo_vincula_y_numera_sin_cambiar_los_datos_antes_despues(): void
    {
        $this->crearReportes(901, 902, 903);
        $antes = json_encode(['DUREZA_ETAPA' => 'ANTES', 'ANTES_A' => 167]);
        $despues = json_encode(['DUREZA_ETAPA' => 'DESPUES', 'ANTES_A' => 167, 'DESPUES_A' => 149]);
        reporte::whereKey(901)->update(['Datos_Equipo' => $antes]);
        reporte::whereKey(902)->update(['Datos_Equipo' => $despues]);
        $series = app(ServicioSerieReportes::class);
        // Un reporte anterior sin serie puede seguir usando el botón de siempre.
        $vinculada = $series->vincularConsecutivo0204(901, 902);
        $this->assertSame(ServicioSerieReportes::FORMATO_02_B_04, $vinculada->formato);
        $this->assertSame(2, $vinculada->numero_orden);
        $series->registrarPaginas(901, 2);
        $this->assertSame(['pagina_inicial' => 3, 'pagina_final' => 5, 'total_estimado' => 5], $series->registrarPaginas(902, 3));
        // Copiar desde un origen previo no bloquea ni transforma su lógica Antes/Después.
        $tercera = $series->vincularConsecutivo0204(901, 903);
        $this->assertSame(3, $tercera->numero_orden);
        $this->assertSame(3, $tercera->cantidad_planificada);
        $this->assertSame($antes, reporte::findOrFail(901)->Datos_Equipo);
        $this->assertSame($despues, reporte::findOrFail(902)->Datos_Equipo);
        $this->assertSame('dato conservado', json_decode(reporte::findOrFail(901)->Detalles_Generales, true)['OTRO_DATO']);
    }

    public function test_0203_numera_tres_piezas_y_actualiza_el_total_si_una_ocupa_dos_hojas(): void
    {
        $this->crearReportes(801, 802, 803);
        $series = app(ServicioSerieReportes::class);
        $series->iniciar(801, 3, ServicioSerieReportes::FORMATO_02_B_03);
        $this->assertSame(['pagina_inicial' => 1, 'pagina_final' => 1, 'total_estimado' => 3], $series->registrarPaginas(801, 1));
        $series->registrarConsecutivo(801, 802);
        $series->registrarPaginas(802, 2);
        $series->registrarConsecutivo(802, 803);
        $this->assertSame(['pagina_inicial' => 4, 'pagina_final' => 4, 'total_estimado' => 4], $series->registrarPaginas(803, 1));
        $this->assertSame(ServicioSerieReportes::FORMATO_02_B_03, $series->obtener(803)->formato);
        $this->assertSame(4, $series->registrarPaginas(801, 1)['total_estimado']);
        $this->assertFalse($series->puedeCrearSiguiente(803));
    }

    public function test_0501_continua_hojas_y_no_copia_resultados_al_siguiente(): void
    {
        $this->crearReportes(701, 702);
        $series = app(ServicioSerieReportes::class);
        $series->iniciar(701, 2, ServicioSerieReportes::FORMATO_05_B_01);
        $this->assertSame(['pagina_inicial' => 1, 'pagina_final' => 2, 'total_estimado' => 4], $series->registrarPaginas(701, 2));
        $series->registrarConsecutivo(701, 702);
        $this->assertSame(['pagina_inicial' => 3, 'pagina_final' => 5, 'total_estimado' => 5], $series->registrarPaginas(702, 3));
        $this->assertSame(ServicioSerieReportes::FORMATO_05_B_01, $series->obtener(702)->formato);
        $this->assertSame(5, $series->registrarPaginas(701, 2)['total_estimado']);
        $this->assertFalse($series->puedeCrearSiguiente(702));
        $norma = ['Seleccion_Quimica' => ['1:base'], 'Tabla' => [['Elemento' => 'C', 'Promedio' => '0.1234']], 'Analisis_PDF' => [['archivo' => 'anterior.pdf']]];
        $juntas = app(\App\Services\ServicioJuntasReporteIM::class)->armar([], $norma, null);
        $metodo = new \ReflectionMethod(\App\Http\Controllers\Reporte\ReporteController::class, 'clonarJuntasParaNuevoReporte');
        $metodo->setAccessible(true);
        $nuevo = json_decode($metodo->invoke(new \App\Http\Controllers\Reporte\ReporteController, $juntas, []), true);
        $this->assertSame(['1:base'], $nuevo['Norma_IM']['Seleccion_Quimica']);
        $this->assertSame('', $nuevo['Norma_IM']['Tabla'][0]['Promedio']);
        $this->assertSame([], $nuevo['Norma_IM']['Analisis_PDF']);
    }

    public function test_0301_numera_pdf_con_hojas_reales_y_conserva_datos_previos(): void
    {
        $this->crearReportes(601, 602, 603);
        $series = app(ServicioSerieReportes::class);
        $series->iniciar(601, 3, ServicioSerieReportes::FORMATO_03_B_01);
        $this->assertSame(3, $series->registrarPaginas(601, 1)['total_estimado']);
        $series->registrarConsecutivo(601, 602);
        $series->registrarPaginas(602, 2);
        $series->registrarConsecutivo(602, 603);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML('<html><body><table><tr><th id="numero-pagina-serie" style="width:100px;height:25px">&nbsp;</th></tr></table><p>PIEZA TRES</p></body></html>');
        $controlador = app(\App\Http\Controllers\Reporte\IM\FOR_PIMP_03_B_01Controller::class);
        $metodo = new \ReflectionMethod($controlador, 'numerarPdfSerie');
        $metodo->setAccessible(true);
        $metodo->invoke($controlador, $pdf, 603);
        $texto = (new \Smalot\PdfParser\Parser())->parseContent($pdf->output())->getText();
        $this->assertStringContainsString('4 DE 4', $texto);
        $this->assertStringContainsString('4 Of 4', $texto);
        $this->assertStringContainsString('PIEZA TRES', $texto);
        $this->assertSame('dato conservado', json_decode(reporte::findOrFail(603)->Detalles_Generales, true)['OTRO_DATO']);
        $this->assertSame(ServicioSerieReportes::FORMATO_03_B_01, $series->obtener(603)->formato);
    }

    public function test_serie_0403_numera_tres_piezas_y_recalcula_al_agregar_fotos(): void
    {
        $this->crearReportes(501, 502, 503);
        $servicio = app(ServicioSerieReportes::class);
        $servicio->iniciar(501, 3, ServicioSerieReportes::FORMATO_04_03);
        $servicio->registrarPaginas(501, 2);
        $servicio->registrarConsecutivo(501, 502);
        $servicio->registrarPaginas(502, 3);
        $servicio->registrarConsecutivo(502, 503);
        $this->assertSame(['pagina_inicial' => 6, 'pagina_final' => 7, 'total_estimado' => 7], $servicio->registrarPaginas(503, 2));
        $this->assertSame(ServicioSerieReportes::FORMATO_04_03, $servicio->obtener(503)->formato);
        $this->assertSame(['pagina_inicial' => 1, 'pagina_final' => 2, 'total_estimado' => 7], $servicio->registrarPaginas(501, 2));
        $servicio->registrarPaginas(502, 4);
        $this->assertSame(['pagina_inicial' => 7, 'pagina_final' => 8, 'total_estimado' => 8], $servicio->registrarPaginas(503, 2));
        $this->assertFalse($servicio->puedeCrearSiguiente(503));
    }

    public function test_serie_0402_conserva_su_formato_y_continua_paginas_con_anexos_variables(): void
    {
        $this->crearReportes(401, 402);
        $servicio = app(ServicioSerieReportes::class);
        $servicio->iniciar(401, 2, ServicioSerieReportes::FORMATO_04_02);
        $this->assertSame(ServicioSerieReportes::FORMATO_04_02, $servicio->obtener(401)->formato);
        $servicio->registrarPaginas(401, 4);
        $servicio->registrarConsecutivo(401, 402);
        $this->assertSame(ServicioSerieReportes::FORMATO_04_02, $servicio->obtener(402)->formato);
        $this->assertSame(2, $servicio->obtener(402)->numero_orden);
        $this->assertSame(['pagina_inicial' => 5, 'pagina_final' => 7, 'total_estimado' => 7], $servicio->registrarPaginas(402, 3));
        $this->assertFalse($servicio->puedeCrearSiguiente(402));
    }

    protected function setUp(): void
    {
        parent::setUp();

        // La prueba usa SQLite en memoria: nunca modifica la base local de SAICO.
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');

        Schema::connection('sqlite')->create('Reportes', function (Blueprint $table): void {
            $table->unsignedBigInteger('idReportes')->primary();
            $table->unsignedBigInteger('idPrueba_Aplica')->nullable();
            $table->longText('Detalles_Generales')->nullable();
            $table->longText('Datos_Equipo')->nullable();
            $table->string('Estatus')->nullable();
        });
    }

    public function test_numera_la_serie_con_paginas_reales_y_estimadas(): void
    {
        $this->crearReportes(101, 102, 103);
        $servicio = app(ServicioSerieReportes::class);
        $servicio->iniciar(101, 3);

        $primero = $servicio->registrarPaginas(101, 2);
        $this->assertSame(['pagina_inicial' => 1, 'pagina_final' => 2, 'total_estimado' => 6], $primero);

        $servicio->registrarConsecutivo(101, 102);
        $segundo = $servicio->registrarPaginas(102, 3);
        $this->assertSame(['pagina_inicial' => 3, 'pagina_final' => 5, 'total_estimado' => 7], $segundo);

        $servicio->registrarConsecutivo(102, 103);
        $tercero = $servicio->registrarPaginas(103, 2);
        $this->assertSame(['pagina_inicial' => 6, 'pagina_final' => 7, 'total_estimado' => 7], $tercero);

        $detalles = json_decode((string) reporte::findOrFail(102)->Detalles_Generales, true);
        $this->assertSame(2, $detalles['SERIE_REPORTES']['numero_orden']);
        $this->assertSame('dato conservado', $detalles['OTRO_DATO']);
        $this->assertFalse(Schema::hasTable('series_reportes'));
    }

    public function test_impide_superar_la_cantidad_planificada(): void
    {
        $this->crearReportes(201, 202);
        $servicio = app(ServicioSerieReportes::class);
        $servicio->iniciar(201, 1);

        $this->expectException(RuntimeException::class);
        $servicio->registrarConsecutivo(201, 202);
    }

    public function test_permite_ampliar_una_serie_creada_inicialmente_con_un_reporte(): void
    {
        $this->crearReportes(211);
        $servicio = app(ServicioSerieReportes::class);
        $servicio->iniciar(211, 1);
        $servicio->registrarPaginas(211, 2);

        $actualizada = $servicio->actualizarCantidad(211, 3);

        $this->assertSame(3, (int) $actualizada->cantidad_planificada);
        $this->assertSame(1, (int) $actualizada->pagina_inicial);
        $this->assertSame(2, (int) $actualizada->pagina_final);
        $this->assertTrue($servicio->puedeCrearSiguiente(211));
    }

    public function test_eliminar_un_integrante_compacta_la_serie_y_permite_reponerlo(): void
    {
        $this->crearReportes(301, 302, 303, 304);
        $servicio = app(ServicioSerieReportes::class);
        $servicio->iniciar(301, 3);
        $servicio->registrarPaginas(301, 2);
        $servicio->registrarConsecutivo(301, 302);
        $servicio->registrarPaginas(302, 3);
        $servicio->registrarConsecutivo(302, 303);
        $servicio->registrarPaginas(303, 2);

        $servicio->eliminarReporte(302);

        $terceroCompactado = $servicio->obtener(303);
        $this->assertSame(2, $terceroCompactado->numero_orden);
        $this->assertSame(3, $terceroCompactado->pagina_inicial);
        $this->assertSame(4, $terceroCompactado->pagina_final);
        $this->assertTrue($servicio->puedeCrearSiguiente(303));

        $repuesto = $servicio->registrarConsecutivo(303, 304);
        $this->assertSame(3, $repuesto->numero_orden);
    }

    public function test_no_permite_crear_desde_un_reporte_anterior_de_la_serie(): void
    {
        $this->crearReportes(401, 402, 403);
        $servicio = app(ServicioSerieReportes::class);
        $servicio->iniciar(401, 3);
        $servicio->registrarConsecutivo(401, 402);

        $this->assertFalse($servicio->esUltimo(401));
        $this->assertTrue($servicio->esUltimo(402));

        $this->expectException(RuntimeException::class);
        $servicio->registrarConsecutivo(401, 403);
    }

    private function crearReportes(int ...$ids): void
    {
        foreach ($ids as $id) {
            reporte::query()->create([
                'idReportes' => $id,
                'Detalles_Generales' => json_encode(['OTRO_DATO' => 'dato conservado']),
                'Estatus' => 'CREADO',
            ]);
        }
    }
}
