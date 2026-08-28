<?php

namespace Tests\Unit;

use App\Jobs\Procesamiento\GenerarReportePdfJob;
use App\Models\Reporte\reporte;
use App\Models\Reporte\Fotos_Reporte;
use App\Services\ServicioPdfGenerado;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServicioPdfGeneradoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');
        Storage::fake('local');

        Schema::create('Reportes', function (Blueprint $table): void {
            $table->unsignedBigInteger('idReportes')->primary();
            $table->unsignedBigInteger('idPrueba_Aplica')->nullable();
            $table->longText('Detalles_Generales')->nullable();
            $table->longText('Datos_Equipo')->nullable();
            $table->string('Estatus')->nullable();
        });
        Schema::create('Grupo_Juntas_Detalles_Re', function (Blueprint $table): void {
            $table->id('idGrupo_Juntas_Detalles_Re');
            $table->unsignedBigInteger('idReportes');
            $table->longText('Juntas_Grupo_Re')->nullable();
        });
        Schema::create('Firmas_Reportes', function (Blueprint $table): void {
            $table->id('idFirmas_Reportes');
            $table->unsignedBigInteger('idReportes');
            $table->longText('Firmas')->nullable();
        });
        Schema::create('Fotos_Reportes', function (Blueprint $table): void {
            $table->id('idFotos_Reportes');
            $table->unsignedBigInteger('idReportes');
            $table->longText('Fotos_Reportes')->nullable();
        });
        Schema::create('trabajos_procesamiento', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('usuario_id');
            $table->string('tipo');
            $table->string('estado');
            $table->string('mensaje')->nullable();
            $table->longText('contexto')->nullable();
            $table->longText('resultado')->nullable();
            $table->longText('error')->nullable();
            $table->timestamp('iniciado_at')->nullable();
            $table->timestamp('completado_at')->nullable();
            $table->timestamp('expira_at')->nullable();
            $table->timestamps();
        });

        reporte::query()->create([
            'idReportes' => 901,
            'Detalles_Generales' => json_encode(['Cliente' => 'CLIENTE UNO']),
            'Datos_Equipo' => json_encode(['Equipo' => 'VANTA']),
            'Estatus' => 'Activo',
        ]);
    }

    public function test_reutiliza_el_pdf_mientras_el_reporte_no_cambie(): void
    {
        $servicio = app(ServicioPdfGenerado::class);

        $this->assertNull($servicio->rutaVigente(901, '06_B_01'));
        $ruta = $servicio->guardar(901, '06_B_01', 'es', '%PDF-1.4 prueba');

        $this->assertFileExists($ruta);
        $this->assertSame($ruta, $servicio->rutaVigente(901, '06_B_01'));
    }

    public function test_un_cambio_de_datos_invalida_la_version_anterior(): void
    {
        $servicio = app(ServicioPdfGenerado::class);
        $servicio->guardar(901, '06_B_01', 'es', '%PDF-1.4 anterior');

        reporte::query()->whereKey(901)->update([
            'Detalles_Generales' => json_encode(['Cliente' => 'CLIENTE CORREGIDO']),
        ]);

        $this->assertNull($servicio->rutaVigente(901, '06_B_01'));
    }

    public function test_un_cambio_de_paginas_en_otro_integrante_invalida_la_serie(): void
    {
        $serie = 'serie-prueba-cache';
        reporte::query()->whereKey(901)->update([
            'Detalles_Generales' => json_encode([
                'Cliente' => 'CLIENTE UNO',
                'SERIE_REPORTES' => ['serie_uuid' => $serie, 'numero_orden' => 1, 'cantidad_planificada' => 2],
            ]),
        ]);
        reporte::query()->create([
            'idReportes' => 902,
            'Detalles_Generales' => json_encode([
                'Cliente' => 'CLIENTE UNO',
                'SERIE_REPORTES' => ['serie_uuid' => $serie, 'numero_orden' => 2, 'cantidad_planificada' => 2],
            ]),
            'Datos_Equipo' => '{}',
            'Estatus' => 'Activo',
        ]);
        Fotos_Reporte::query()->create([
            'idReportes' => 902,
            'Fotos_Reportes' => '[]',
        ]);

        $servicio = app(ServicioPdfGenerado::class);
        $servicio->guardar(901, '06_B_01', 'es', '%PDF-1.4 serie');
        $this->assertNotNull($servicio->rutaVigente(901, '06_B_01'));

        Fotos_Reporte::query()->where('idReportes', 902)->update([
            'Fotos_Reportes' => json_encode([['pagina' => 2, 'posicion' => 'arriba_izquierda']]),
        ]);

        $this->assertNull($servicio->rutaVigente(901, '06_B_01'));
    }

    public function test_no_programa_dos_trabajos_para_la_misma_version(): void
    {
        Queue::fake();
        $servicio = app(ServicioPdfGenerado::class);

        $primero = $servicio->programar(901, '06_B_01', 'es', 77);
        $segundo = $servicio->programar(901, '06_B_01', 'es', 77);

        $this->assertNotNull($primero);
        $this->assertSame($primero->id, $segundo?->id);
        Queue::assertPushed(GenerarReportePdfJob::class, 1);
    }
}
