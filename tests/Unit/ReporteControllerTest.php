<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\ReporteController;
use PHPUnit\Framework\TestCase;

class ReporteControllerTest extends TestCase
{
    public function test_generar_numero_reporte_consecutivo()
    {
        $controller = new ReporteController();

        $method = new \ReflectionMethod($controller, 'generarNuevoNoReporte');
        $method->setAccessible(true);

        $this->assertSame('002-PRUEBA', $method->invoke($controller, '001-PRUEBA'));
        $this->assertSame('001-PRUEBA', $method->invoke($controller, 'PRUEBA'));
    }
}
