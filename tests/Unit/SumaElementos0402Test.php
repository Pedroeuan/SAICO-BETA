<?php

namespace Tests\Unit;

use App\Services\SumaElementos0402;
use Tests\TestCase;

class SumaElementos0402Test extends TestCase
{
    public function test_respeta_la_suma_editada_por_el_tecnico_incluso_si_la_deja_vacia(): void
    {
        $pdf = ['Cr' => ['calculable' => true, 'promedio' => .0467], 'Mo' => ['calculable' => true, 'promedio' => .0410]];
        foreach (['0.0900', ''] as $valor) {
            $filas = [['Elemento' => 'Cr + Mo', 'Promedio' => $valor, 'Origen' => 'calculado']];
            $resultado = SumaElementos0402::completar($filas, $pdf, [], ['Cr + Mo' => true]);
            $this->assertSame($valor, $resultado[0]['Promedio']);
            $this->assertSame($valor === '' ? 'pendiente_manual' : 'manual', $resultado[0]['Origen']);
        }
    }

    public function test_suma_promedios_sin_cambiar_los_elementos_individuales(): void
    {
        $valores = ['Cu' => '0.0687', 'Ni' => '0.0367', 'Cr' => '0.0467', 'Mo' => '0.0410', 'V' => '0.0040'];
        $this->assertSame('0.1971', SumaElementos0402::calcular('Cu + Ni + Cr + Mo + V', $valores));
        $this->assertSame('0.0877', SumaElementos0402::calcular('Cr+Mo', $valores));
        $this->assertNull(SumaElementos0402::calcular('Cu', $valores));
        $this->assertSame('0.0687', $valores['Cu']);
    }

    public function test_conserva_el_limite_inferior_y_no_inventa_valores_faltantes(): void
    {
        $this->assertSame('[C<]0.0450', SumaElementos0402::calcular('Mo + V', ['Mo' => '0.0410', 'V' => '[C<]0.0040']));
        $this->assertNull(SumaElementos0402::calcular('Mo + V', ['Mo' => '0.0410']));
        $this->assertNull(SumaElementos0402::calcular('Mo + V', ['Mo' => '0.0410', 'V' => '--']));
    }

    public function test_recalcula_al_guardar_con_elementos_del_pdf_no_visibles_en_la_norma(): void
    {
        $filas = [['Elemento' => 'Cr + Mo', 'Promedio' => '999', 'Origen' => 'manual']];
        $pdf = ['Cr' => ['calculable' => true, 'promedio' => .0467], 'Mo' => ['calculable' => true, 'promedio' => .0410]];
        $resultado = SumaElementos0402::completar($filas, $pdf);
        $this->assertSame('0.0877', $resultado[0]['Promedio']);
        $this->assertSame('calculado', $resultado[0]['Origen']);
        $this->assertSame('0.0967', SumaElementos0402::completar($filas, ['Cr' => $pdf['Cr']], ['mo' => '0.0500'])[0]['Promedio']);
    }
}
