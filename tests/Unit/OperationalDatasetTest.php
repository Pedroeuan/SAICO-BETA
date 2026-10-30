<?php

namespace Tests\Unit;

use App\Services\Analytics\OperationalDataset;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

class OperationalDatasetTest extends TestCase
{
    private function evaluate(?array $events = null, array $override = [], bool $checks = true): array
    {
        $exit = array_merge(['id' => 1, 'estatus' => 'finalizado', 'vehicle_exists' => 1, 'created_at' => '2026-10-01 08:00:00', 'fecha_regreso' => '2026-10-01 14:00:00'], $override);
        return OperationalDataset::row($exit, $events ?? $this->events(), $checks, new DateTimeImmutable('2026-10-08 12:00:00', new DateTimeZone('America/Mexico_City')));
    }

    private function events(): array
    {
        return [
            ['tipo' => 'salida', 'ocurrido_at' => '2026-10-01 09:00:00', 'registrado_at' => '2026-10-01 09:10:00', 'zona_horaria' => 'America/Mexico_City'],
            ['tipo' => 'entrada', 'ocurrido_at' => '2026-10-01 10:00:00', 'registrado_at' => '2026-10-01 12:00:00', 'zona_horaria' => 'America/Mexico_City'],
        ];
    }

    public function test_elapsed_time_uses_events_not_capture_or_closure(): void
    {
        $r = $this->evaluate();
        $this->assertSame(60.0, $r['duracion_operativa_minutos']);
        $this->assertSame(10.0, $r['retraso_captura_salida_minutos']);
        $this->assertSame(120.0, $r['retraso_captura_regreso_minutos']);
        $this->assertSame('2026-10-01T14:00:00-06:00', $r['etiqueta_disponible_desde']);
        $this->assertSame(0, $r['aprobado_entrenamiento']);
    }

    public function test_missing_duplicate_active_or_unpaired_data_never_becomes_zero_duration(): void
    {
        foreach ([[], [$this->events()[0]], [...$this->events(), $this->events()[1]]] as $events) {
            $this->assertNull($this->evaluate($events)['duracion_operativa_minutos']);
        }
        $this->assertNull($this->evaluate(null, ['estatus' => 'activo'])['duracion_operativa_minutos']);
        $this->assertNull($this->evaluate(null, [], false)['duracion_operativa_minutos']);
        $this->assertNull($this->evaluate(null, ['vehicle_exists' => 0])['duracion_operativa_minutos']);
    }

    public function test_temporal_errors_and_invalid_zones_are_excluded(): void
    {
        foreach ([
            ['ocurrido_at', '2026-02-30 10:00:00'],
            ['ocurrido_at', '2026-10-01 08:00:00'],
            ['registrado_at', '2026-10-09 12:00:00'],
            ['registrado_at', '2026-10-01 09:30:00'],
            ['zona_horaria', 'Invalid/Zone'],
        ] as [$field, $value]) {
            $events = $this->events(); $events[1][$field] = $value;
            $r = $this->evaluate($events);
            $this->assertSame(0, $r['elegible_kpi_operativo']);
            $this->assertNull($r['etiqueta_disponible_desde']);
        }
        $this->assertNull($this->evaluate(null, ['created_at' => '2026-10-02 08:00:00'])['duracion_operativa_minutos']);
        $this->assertNull($this->evaluate(null, ['fecha_regreso' => '2026-10-09 12:00:00'])['duracion_operativa_minutos']);
    }

    public function test_timezone_conversion_and_long_duration_review(): void
    {
        $events = $this->events();
        $events[1] = ['tipo' => 'entrada', 'ocurrido_at' => '2026-10-02 16:00:00', 'registrado_at' => '2026-10-02 17:00:00', 'zona_horaria' => 'UTC'];
        $r = $this->evaluate($events, ['fecha_regreso' => '2026-10-02 12:00:00']);
        $this->assertSame(1500.0, $r['duracion_operativa_minutos']);
        $this->assertSame(1, $r['requiere_revision_24h']);
    }
}
