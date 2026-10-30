<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\Analytics\SalidasDataset;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MobileAnalyticsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'analytics_test', 'database.connections.analytics_test' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true]]);
        $this->assertSame(':memory:', DB::connection()->getDatabaseName());
        Schema::create('users', function (Blueprint $t) {
            $t->id();
            $t->string('rol');
        });
        Schema::create('vehiculos', function (Blueprint $t) {
            $t->string('placa')->nullable(); $t->string('marca')->nullable(); $t->string('modelo')->nullable();
            $t->id();
        });
        Schema::create('salidas_vehiculos', function (Blueprint $t) {
            $t->id();
            $t->integer('vehiculo_id');
            $t->integer('chofer_id');
            $t->integer('solicitado_por');
            $t->string('estatus');
            $t->dateTime('fecha_salida');
            $t->dateTime('fecha_regreso')->nullable();
            $t->double('duracion_minutos')->nullable();
            $t->dateTime('created_at');
        });
        Schema::create('salidas_checklists', function (Blueprint $t) {
            $t->id();
            $t->integer('salida_vehiculo_id');
            $t->string('tipo');
        });
        Schema::create('checklist_condiciones', function (Blueprint $t) {
            $t->id();
            $t->integer('salida_checklist_id');
            $t->integer('kilometraje');
            foreach (array_keys(SalidasDataset::CONDITIONS) as $name) {
                $t->string($name)->nullable();
            }
        });
        DB::table('users')->insert([['id' => 1, 'rol' => 'operativo'], ['id' => 2, 'rol' => 'operativo']]);
        DB::table('vehiculos')->insert([['id' => 1], ['id' => 2]]);
        (require database_path('migrations/2026_10_08_000001_create_salidas_tiempos_operativos_table.php'))->up();
        $this->seedExit(1, 1, 1, '2026-01-05 10:00:00', '2026-01-05 11:00:00', 10);
        $this->seedExit(2, 1, 1, '2026-01-05 12:00:00', '2026-01-05 11:30:00', 5);
        $this->seedExit(3, 1, 1, '2026-01-05 23:59:59', '2026-01-07 23:59:59', 20);
        $this->seedExit(4, 1, 1, '2026-01-06 09:00:00', null, 0);
        $this->seedExit(5, 2, 2, '2026-01-06 10:00:00', '2026-01-06 12:00:00', 7);
    }

    private function seedExit(int $id, int $user, int $vehicle, string $start, ?string $end, int $km): void
    {
        DB::table('salidas_vehiculos')->insert(['id' => $id, 'vehiculo_id' => $vehicle, 'chofer_id' => $user, 'solicitado_por' => $user, 'estatus' => $end ? 'finalizado' : 'activo', 'fecha_salida' => $start, 'fecha_regreso' => $end, 'duracion_minutos' => $end ? (strtotime($end) - strtotime($start)) / 60 : null, 'created_at' => '2026-01-01 10:00:00']);
        foreach ($end ? ['salida', 'entrada'] : ['salida'] as $type) {
            $check = DB::table('salidas_checklists')->insertGetId(['salida_vehiculo_id' => $id, 'tipo' => $type]);
            $condition = ['salida_checklist_id' => $check, 'kilometraje' => $type === 'salida' ? 100 : 100 + $km];
            foreach (SalidasDataset::CONDITIONS as $key => $values) {
                $condition[$key] = $values[0];
            }
            if ($id === 2 && $type === 'entrada') {
                $condition['liquido_frenos'] = '';
            }
            DB::table('checklist_condiciones')->insert($condition);
        }
    }

    private function login(string $role = 'operativo'): void
    {
        Sanctum::actingAs((new User)->forceFill(['id' => 1, 'rol' => $role]));
    }

    public function test_vehicle_options_respect_history_visibility(): void
    {
        $this->getJson('/api/mobile/v1/analitica/vehiculos')->assertUnauthorized();
        $this->login();
        $this->getJson('/api/mobile/v1/analitica/vehiculos')->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.id', 1);
        $this->login('Administrador');
        $this->getJson('/api/mobile/v1/analitica/vehiculos')->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_operational_duration_is_separate_scoped_and_nullable(): void
    {
        $this->login();
        $this->getJson('/api/mobile/v1/analitica/kpis')->assertOk()
            ->assertJsonPath('data.indicadores.duracion_operativa.valor', null)
            ->assertJsonPath('data.indicadores.cobertura_operativa.valor', 0);
        foreach ([1, 5] as $id) {
            foreach (['salida' => '10:00:00', 'entrada' => '11:00:00'] as $type => $hour) {
                DB::table('salidas_tiempos_operativos')->insert([
                    'salida_vehiculo_id' => $id, 'tipo' => $type,
                    'ocurrido_at' => '2026-01-05 '.$hour,
                    'registrado_at' => '2026-01-05 '.($type === 'salida' ? '10:10:00' : '11:00:00'),
                    'canal' => 'mobile', 'entorno' => 'testing', 'zona_horaria' => 'America/Mexico_City', 'contexto' => '{}',
                ]);
            }
        }
        $this->getJson('/api/mobile/v1/analitica/kpis')->assertOk()
            ->assertJsonPath('data.indicadores.duracion_operativa.valor', 60)
            ->assertJsonPath('data.indicadores.duracion_operativa.registros_validos', 1)
            ->assertJsonPath('data.indicadores.cobertura_operativa.valor', 33.33)
            ->assertJsonPath('data.indicadores.duracion_promedio.valor', 1470);
        $this->getJson('/api/mobile/v1/analitica/kpis?vehiculo_id=2')->assertOk()
            ->assertJsonPath('data.indicadores.duracion_operativa.valor', null)
            ->assertJsonPath('data.indicadores.cobertura_operativa.valor', null);
        $this->getJson('/api/mobile/v1/analitica/kpis?desde=2026-01-06')->assertOk()
            ->assertJsonPath('data.indicadores.duracion_operativa.valor', null);
        $this->login('Administrador');
        $this->getJson('/api/mobile/v1/analitica/kpis')->assertOk()
            ->assertJsonPath('data.indicadores.duracion_operativa.registros_validos', 2);
        DB::table('salidas_checklists')->insert(['salida_vehiculo_id' => 1, 'tipo' => 'entrada']);
        $this->getJson('/api/mobile/v1/analitica/kpis')->assertOk()
            ->assertJsonPath('data.indicadores.duracion_operativa.registros_validos', 1);
    }

    public function test_authentication_is_required(): void
    {
        $this->getJson('/api/mobile/v1/analitica/kpis')->assertUnauthorized();
    }

    public function test_scope_denominators_quality_and_read_only_behavior(): void
    {
        $before = DB::table('salidas_vehiculos')->orderBy('id')->get()->toJson();
        $this->login();
        $response = $this->getJson('/api/mobile/v1/analitica/kpis')->assertOk()
            ->assertJsonPath('data.indicadores.salidas_total.valor', 4)
            ->assertJsonPath('data.indicadores.salidas_finalizadas.valor', 3)
            ->assertJsonPath('data.indicadores.salidas_activas.valor', 1)
            ->assertJsonPath('data.indicadores.kilometros_registrados.valor', 35)
            ->assertJsonPath('data.indicadores.duracion_promedio.valor', 1470)
            ->assertJsonPath('data.indicadores.duracion_promedio.registros_excluidos', 1)
            ->assertJsonPath('data.indicadores.duracion_promedio.intervalos_mayores_24h', 1)
            ->assertJsonPath('data.indicadores.cobertura_checklists.valor', 100)
            ->assertJsonPath('data.calidad.conteos_por_alerta.entrada_liquido_frenos_faltante', 1);
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertArrayNotHasKey('no_finalizada', $response->json('data.calidad.conteos_por_alerta'));
        $this->assertSame($before, DB::table('salidas_vehiculos')->orderBy('id')->get()->toJson());
    }

    public function test_admin_scope_and_inclusive_date_filter(): void
    {
        $this->login('Administrador');
        $this->getJson('/api/mobile/v1/analitica/kpis')->assertOk()
            ->assertJsonPath('data.indicadores.salidas_total.valor', 5)
            ->assertJsonPath('data.indicadores.kilometros_registrados.valor', 42);
        $this->getJson('/api/mobile/v1/analitica/kpis?desde=2026-01-05&hasta=2026-01-05')->assertOk()
            ->assertJsonPath('data.indicadores.salidas_total.valor', 3)
            ->assertJsonPath('data.serie_mensual.0.finalizadas', 3);
        $this->getJson('/api/mobile/v1/analitica/kpis?vehiculo_id=2')->assertOk()
            ->assertJsonPath('data.indicadores.salidas_total.valor', 1);
    }

    public function test_vehicle_filter_cannot_expand_access_and_empty_metrics_are_null(): void
    {
        $this->login();
        foreach ([2, 999] as $id) {
            $this->getJson('/api/mobile/v1/analitica/kpis?vehiculo_id='.$id)->assertOk()
                ->assertJsonPath('data.sin_datos', true)
                ->assertJsonPath('data.indicadores.salidas_total.valor', 0)
                ->assertJsonPath('data.indicadores.kilometros_registrados.valor', null)
                ->assertJsonPath('data.indicadores.duracion_promedio.valor', null)
                ->assertJsonPath('data.indicadores.cobertura_checklists.valor', null);
        }
        DB::table('salidas_vehiculos')->where('id', 5)->update(['solicitado_por' => 1]);
        $this->getJson('/api/mobile/v1/analitica/kpis?vehiculo_id=2')->assertOk()->assertJsonPath('data.indicadores.salidas_total.valor', 1);
    }

    public function test_invalid_filters_are_rejected(): void
    {
        $this->login();
        foreach (['desde=2026-02-30', 'desde=2026-02-01&hasta=2026-01-01', 'vehiculo_id=-1', 'vehiculo_id=abc', 'hasta=bad'] as $query) {
            $this->getJson('/api/mobile/v1/analitica/kpis?'.$query)->assertUnprocessable();
        }
    }

    public function test_duplicate_checklists_do_not_multiply_mileage_or_coverage(): void
    {
        $this->login();
        DB::table('salidas_checklists')->insert(['salida_vehiculo_id' => 1, 'tipo' => 'entrada']);
        $this->getJson('/api/mobile/v1/analitica/kpis')->assertOk()
            ->assertJsonPath('data.indicadores.kilometros_registrados.valor',25)
            ->assertJsonPath('data.indicadores.kilometros_registrados.registros_excluidos',1)
            ->assertJsonPath('data.indicadores.cobertura_checklists.numerador',2)
            ->assertJsonPath('data.indicadores.cobertura_checklists.denominador',3);
    }
}
