<?php

namespace Tests\Unit;

use App\Services\SeleccionReferencias0402;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SeleccionReferencias0402Test extends TestCase
{
    private function candidatas(): array
    {
        return [
            ['idnormas_im' => 1, 'fila' => 0, 'norma' => 'ASTM A36', 'tension_min' => 58],
            ['idnormas_im' => 2, 'fila' => 0, 'norma' => 'ASTM A285 Grado A', 'tension_min' => 45],
            ['idnormas_im' => 2, 'fila' => 1, 'norma' => 'ASTM A285 Grado B', 'tension_min' => 50],
        ];
    }

    public function test_guarda_solo_las_marcadas_y_en_el_orden_seleccionado(): void
    {
        $resultado = SeleccionReferencias0402::filtrar($this->candidatas(), '["2:1","1:0"]');
        $this->assertCount(2, $resultado);
        $this->assertSame('ASTM A285 Grado B', $resultado[0]['norma']);
        $this->assertSame(58, $resultado[1]['tension_min']);
    }

    public function test_sin_seleccion_la_tabla_queda_vacia(): void
    {
        $this->assertSame([], SeleccionReferencias0402::filtrar($this->candidatas(), '[]'));
    }

    public function test_no_acepta_una_norma_que_no_coincide(): void
    {
        $this->expectException(ValidationException::class);
        SeleccionReferencias0402::filtrar($this->candidatas(), '["99:0"]');
    }

    public function test_no_acepta_referencias_repetidas(): void
    {
        $this->expectException(ValidationException::class);
        SeleccionReferencias0402::filtrar($this->candidatas(), '["1:0","1:0"]');
    }
}
