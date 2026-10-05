<?php

namespace Tests\Unit;

use App\Services\ComparacionEspecificacion;
use PHPUnit\Framework\TestCase;

class ComparacionEspecificacionTest extends TestCase
{
    public function test_ejemplo_indicado_no_coincide(): void
    {
        $this->assertSame('no_coincide', ComparacionEspecificacion::comparar('ASTM A316', 'ASTM A182', 'F 316L'));
    }

    public function test_compara_la_referencia_completa(): void
    {
        $this->assertSame('coincide', ComparacionEspecificacion::comparar(' astm  a182 · f 316l ', 'ASTM A182', 'F 316L'));
        $this->assertSame('no_coincide', ComparacionEspecificacion::comparar('ASTM A182', 'ASTM A182', 'F 316L'));
    }

    public function test_datos_ausentes_no_son_aceptados_ni_rechazados(): void
    {
        $this->assertSame('sin_datos', ComparacionEspecificacion::comparar('', 'ASTM A182'));
        $this->assertSame('sin_datos', ComparacionEspecificacion::comparar('ASTM A182', ''));
    }

    public function test_separador_visual_no_cambia_la_identificacion(): void
    {
        $this->assertSame('coincide', ComparacionEspecificacion::comparar('ASTM A312 TP 304L', 'ASTM A312', 'TP 304L'));
        $this->assertSame('coincide', ComparacionEspecificacion::comparar('ASTM A312 · TP 304L', 'ASTM A312', 'TP 304L'));
        $this->assertSame('no_coincide', ComparacionEspecificacion::comparar('ASTM A312 TP 316L', 'ASTM A312', 'TP 304L'));
    }

    public function test_espacios_en_designacion_no_cambian_el_grado(): void
    {
        $this->assertSame('coincide', ComparacionEspecificacion::comparar('AWS A5.9 ER 316L', 'AWS A5.9', 'ER316L'));
        $this->assertSame('no_coincide', ComparacionEspecificacion::comparar('AWS A5.9 ER 308L', 'AWS A5.9', 'ER316L'));
        $this->assertSame('no_coincide', ComparacionEspecificacion::comparar('AWS A5.18 ER 316L', 'AWS A5.9', 'ER316L'));
    }
}
