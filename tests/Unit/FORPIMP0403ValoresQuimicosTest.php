<?php

namespace Tests\Unit;

use App\Services\ServicioAnalisisPdfXrf;
use Smalot\PdfParser\Parser;
use PHPUnit\Framework\TestCase;

class FORPIMP0403ValoresQuimicosTest extends TestCase
{
    public function test_conserva_nd_y_la_captura_manual_sin_sumar_elementos_agrupados(): void
    {
        $servicio = new ServicioAnalisisPdfXrf(new Parser);
        $nd = ['cantidad' => 0, 'esperados' => 3, 'promedio' => null, 'valor_reporte' => 'ND'];
        $this->assertSame('ND', $servicio->valorParaReporte0403($nd, 'ND'));
        $this->assertSame('ND', $servicio->valorParaReporte0403($nd));
        $this->assertSame('0.1234', $servicio->valorParaReporte0403($nd, '0.1234'));
        $this->assertSame('ND', $servicio->valorParaReporte0403(null, 'ND'));
        $this->assertSame('0.5217', $servicio->valorParaReporte0403(['cantidad' => 3, 'esperados' => 3, 'promedio' => 0.5217], '99'));
    }
}
