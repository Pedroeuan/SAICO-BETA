<?php

namespace Tests\Unit;

use App\Services\ComposicionesReferencias0402;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ComposicionesReferencias0402Test extends TestCase
{
    public function test_notas_conservan_texto_y_color_de_su_referencia_sin_incluir_vacias(): void
    {
        $referencias = [
            ['norma' => 'Norma A', 'composicion_seleccionada' => ['Observaciones' => '']],
            ['norma' => 'Norma B', 'composicion_seleccionada' => ['Observaciones' => "Según pedido\nAplicable a placas"]],
        ];
        $this->assertSame([['indice' => 1, 'norma' => 'Norma B', 'texto' => "Según pedido\nAplicable a placas"]], ComposicionesReferencias0402::notas($referencias));
    }

    private function referencia(int $id, array $opciones): array
    {
        return ['idnormas_im' => $id, 'fila' => 0, 'norma' => 'Norma '.$id, 'composiciones' => $opciones];
    }

    private function opcion(int $indice, string $valor, string $elemento = 'C'): array
    {
        return ['configuracion' => $indice, 'Variable' => 'Grado '.$indice, 'Tabla' => [['Elemento' => $elemento, 'Composicion' => $valor]]];
    }

    public function test_reune_elementos_y_conserva_el_indice_de_color_de_cada_referencia(): void
    {
        $referencias = ComposicionesReferencias0402::seleccionar([
            $this->referencia(1, [$this->opcion(0, '0.17 % máximo')]),
            $this->referencia(2, [$this->opcion(0, '0.22 % máximo'), $this->opcion(1, '0.28 % máximo')]),
            $this->referencia(3, [$this->opcion(0, '0.90 % máximo', 'Mn')]),
        ], ['2:0' => '1']);
        $filas = ComposicionesReferencias0402::filas($referencias);
        $this->assertSame(['C', 'Mn'], array_column($filas, 'Elemento'));
        $this->assertSame(['0.17 % máximo', '0.28 % máximo'], array_column($filas[0]['Referencias'], 'valor'));
        $this->assertSame(2, $filas[1]['Referencias'][0]['indice']);
    }

    public function test_no_elige_un_grado_cuando_hay_varias_opciones(): void
    {
        $referencias = ComposicionesReferencias0402::seleccionar([$this->referencia(1, [$this->opcion(0, 'A'), $this->opcion(1, 'B')])], []);
        $this->assertSame([], ComposicionesReferencias0402::filas($referencias));
    }

    public function test_rechaza_composicion_ajena_a_la_norma(): void
    {
        $this->expectException(ValidationException::class);
        ComposicionesReferencias0402::seleccionar([$this->referencia(1, [$this->opcion(0, 'A')])], ['1:0' => '25']);
    }
}
