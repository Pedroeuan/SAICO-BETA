<?php

namespace Tests\Unit;

use App\Services\EstadoDocumentacion;
use PHPUnit\Framework\TestCase;

class EstadoDocumentacionTest extends TestCase
{
    public function test_una_orden_sin_reportes_no_se_considera_firmada(): void
    {
        $this->assertSame('sin_reportes', EstadoDocumentacion::calcular(collect()));
    }

    public function test_una_firma_faltante_mantiene_la_orden_pendiente(): void
    {
        $this->assertSame('pendientes', EstadoDocumentacion::calcular(collect([
            (object) ['firmado' => true], (object) ['firmado' => false],
        ])));
    }

    public function test_la_orden_requiere_todos_los_reportes_firmados(): void
    {
        $this->assertSame('firmados', EstadoDocumentacion::calcular(collect([
            (object) ['firmado' => true], (object) ['firmado' => true],
        ])));
    }
}
