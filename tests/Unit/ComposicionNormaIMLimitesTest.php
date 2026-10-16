<?php

namespace Tests\Unit;

use App\Services\ComposicionNormaIM;
use PHPUnit\Framework\TestCase;

class ComposicionNormaIMLimitesTest extends TestCase
{
    public function test_clasifica_limites_anteriores_y_conserva_precision_condiciones_y_texto_ambiguo(): void
    {
        $filas = ComposicionNormaIM::desdeFilas([
            ['Elemento' => 'C', 'Composicion' => '0.25 máx.'],
            ['Elemento' => 'P', 'Composicion' => '0.030 % máximo'],
            ['Elemento' => 'Cu', 'Composicion' => '0,20 mín.'],
            ['Elemento' => 'Mn', 'Composicion' => '0.80–1.20'],
            ['Elemento' => 'Si', 'Composicion' => 'Consultar nota 3'],
        ])[0]['elementos'];
        $this->assertSame('0.25', $filas[0]['maximo']);
        $this->assertSame('0.030', $filas[1]['maximo']);
        $this->assertSame('0.20', $filas[2]['minimo']);
        $this->assertSame('0.80', $filas[3]['minimo']);
        $this->assertSame('1.20', $filas[3]['maximo']);
        $this->assertSame('', $filas[0]['criterio']);
        $this->assertSame('Consultar nota 3', $filas[4]['criterio']);
        $this->assertNull($filas[4]['maximo']);
    }

    public function test_respeta_limites_capturados_y_abrevia_palabras_solo_para_presentacion(): void
    {
        $config = [['elementos' => [['minimo' => 0, 'maximo' => null, 'criterio' => '0.25 máximo', 'condicion' => 'Según pedido']]]];
        $this->assertSame($config, ComposicionNormaIM::normalizarLimites($config));
        $this->assertSame('0.20 % mín. / 0.25 % máx.', ComposicionNormaIM::abreviarLimites('0.20 % mínimo / 0.25 % máximo'));
    }
}
