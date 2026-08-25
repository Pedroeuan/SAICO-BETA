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
