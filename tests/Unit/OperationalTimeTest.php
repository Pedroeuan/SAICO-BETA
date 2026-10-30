<?php

namespace Tests\Unit;

use App\Models\Vehiculos\SalidaVehiculo;
use App\Services\Analytics\OperationalTimeService;
use Carbon\CarbonImmutable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class OperationalTimeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'operational_test', 'database.connections.operational_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ], 'app.timezone' => 'America/Mexico_City']);
        $this->assertSame(':memory:', DB::connection()->getDatabaseName());
        Schema::create('salidas_vehiculos', function (Blueprint $t) {
            $t->id(); $t->integer('vehiculo_id'); $t->string('estatus');
            $t->dateTime('fecha_salida'); $t->timestamps();
        });
        Schema::create('salidas_checklists', function (Blueprint $t) {
            $t->id(); $t->integer('salida_vehiculo_id'); $t->string('tipo');
        });
        (require database_path('migrations/2026_10_08_000001_create_salidas_tiempos_operativos_table.php'))->up();
        DB::table('salidas_vehiculos')->insert([
            'id' => 1, 'vehiculo_id' => 7, 'estatus' => 'activo',
            'fecha_salida' => '2026-10-08 09:00:00', 'created_at' => '2026-10-08 08:00:00',
        ]);
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-10-08 12:00:00', 'America/Mexico_City'));
    }

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();
        DB::purge('operational_test');
        parent::tearDown();
    }

    private function record(string $type, ?string $time): void
    {
        DB::transaction(fn () => app(OperationalTimeService::class)->record(SalidaVehiculo::findOrFail(1), $type, $time, 'mobile'));
    }

    public function test_real_time_is_separate_from_capture_and_timezone_is_normalized(): void
    {
        $this->record('salida', '2026-10-08T15:30:00Z');
        $row = DB::table('salidas_tiempos_operativos')->first();
        $this->assertSame('2026-10-08 09:30:00', $row->ocurrido_at);
        $this->assertSame('2026-10-08 12:00:00', $row->registrado_at);
        $this->assertSame('2026-10-08 09:00:00', DB::table('salidas_vehiculos')->value('fecha_salida'));
        $this->assertSame('captura_checklist', json_decode($row->contexto, true)['instante']);
        $this->record('entrada', '2026-10-08T17:00:00Z');
        $this->assertSame(2, DB::table('salidas_tiempos_operativos')->count());
    }

    public function test_old_client_does_not_invent_operational_time(): void
    {
        $this->record('salida', null);
        $this->assertSame(0, DB::table('salidas_tiempos_operativos')->count());
    }

    public function test_invalid_dates_are_rejected_without_writing(): void
    {
        foreach (['invalid', '2026-10-08 07:59:00', '2026-10-08 12:01:00'] as $value) {
            try {
                $this->record('salida', $value);
                $this->fail('Invalid time accepted');
            } catch (ValidationException $e) {
                $this->assertArrayHasKey('fecha_operativa', $e->errors());
            }
        }
        $this->assertSame(0, DB::table('salidas_tiempos_operativos')->count());
    }

    public function test_arrival_cannot_precede_real_departure(): void
    {
        $this->record('salida', '2026-10-08 10:00:00');
        $this->expectException(ValidationException::class);
        $this->record('entrada', '2026-10-08 09:59:00');
    }

    public function test_existing_checklist_cannot_be_overwritten(): void
    {
        DB::table('salidas_checklists')->insert(['salida_vehiculo_id' => 1, 'tipo' => 'salida']);
        $this->expectException(ValidationException::class);
        $this->record('salida', '2026-10-08 10:00:00');
    }

    public function test_failed_checklist_transaction_rolls_back_time(): void
    {
        try {
            DB::transaction(function () {
                $this->record('salida', '2026-10-08 10:00:00');
                throw new \RuntimeException('Checklist failed');
            });
        } catch (\RuntimeException $e) {
            $this->assertSame('Checklist failed', $e->getMessage());
        }
        $this->assertSame(0, DB::table('salidas_tiempos_operativos')->count());
    }
}
