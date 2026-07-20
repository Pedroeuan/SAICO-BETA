<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\IM\FOR_PIMP_02_B_04Controller;
use ReflectionMethod;
use Tests\TestCase;

class FORPIMP02B04DurezaPromedioTest extends TestCase
{
    public function test_promedio_posterior_conserva_los_valores_anteriores(): void
    {
        $controller = new FOR_PIMP_02_B_04Controller();
        $method = new ReflectionMethod($controller, 'calculateDurezaPromedio');
        $method->setAccessible(true);

        $resultado = $method->invoke($controller, [
            'ANTES_A' => '100',
            'ANTES_B' => '110',
            'ANTES_C' => '120',
            'ANTES_B1' => '130',
            'ANTES_BM' => '140',
            'DESPUES_A' => '',
            'DESPUES_B' => '',
            'DESPUES_C' => '',
            'DESPUES_B1' => '',
            'DESPUES_BM' => '',
            0 => [
                'metal_base_a' => '150',
                'zac_b' => '160',
                'soldadura_c' => '170',
                'zac_b1' => '180',
                'metal_base_a1' => '190',
            ],
            1 => [
                'metal_base_a' => '152',
                'zac_b' => '162',
                'soldadura_c' => '172',
                'zac_b1' => '182',
                'metal_base_a1' => '192',
            ],
        ], 'DESPUES');

        $this->assertSame('100', $resultado['ANTES_A']);
        $this->assertSame('110', $resultado['ANTES_B']);
        $this->assertSame('120', $resultado['ANTES_C']);
        $this->assertSame('130', $resultado['ANTES_B1']);
        $this->assertSame('140', $resultado['ANTES_BM']);
        $this->assertSame('151', $resultado['DESPUES_A']);
        $this->assertSame('161', $resultado['DESPUES_B']);
        $this->assertSame('171', $resultado['DESPUES_C']);
        $this->assertSame('181', $resultado['DESPUES_B1']);
        $this->assertSame('191', $resultado['DESPUES_BM']);
    }
}
