<?php

namespace Tests\Unit;

use App\Models\Normas_IM\Normas_IM;
use App\Services\CoincidenciasMecanicas0402;
use Tests\TestCase;

class CoincidenciasMecanicas0402Test extends TestCase
{
    public function test_incluye_normas_anteriores_con_su_composicion_sin_inventar_valores_mecanicos(): void
    {
        $norma = $this->norma();
        $norma->Variable = 'F 316L';
        $norma->Tablas_Adicionales = null;
        $norma->Tabla = json_encode([['Elemento' => 'C', 'Composicion' => '0.030 % máximo']]);
        $filas = (new CoincidenciasMecanicas0402)->catalogo([$norma]);
        $this->assertCount(1, $filas);
        $this->assertStringContainsString('F 316L', $filas[0]['norma']);
        $this->assertNull($filas[0]['tension_min']);
        $this->assertNull($filas[0]['cedencia_min']);
        $this->assertNull($filas[0]['dureza_max']);
        $seleccion = \App\Services\ComposicionesReferencias0402::seleccionar($filas, []);
        $this->assertSame('0.030 % máximo', \App\Services\ComposicionesReferencias0402::filas($seleccion)[0]['Referencias'][0]['valor']);
    }

    public function test_muestra_grados_quimicos_sin_exigir_tabla_mecanica(): void
    {
        $norma = $this->norma();
        $norma->Tablas_Adicionales = ['quimica_configuraciones' => [
            ['grado' => 'A', 'elementos' => [['elemento' => 'C', 'maximo' => '0.17']]],
            ['grado' => 'B', 'elementos' => [['elemento' => 'C', 'maximo' => '0.22']]],
        ]];
        $filas = (new CoincidenciasMecanicas0402)->catalogo([$norma]);
        $this->assertCount(2, $filas);
        $this->assertCount(1, $filas[0]['composiciones']);
        $this->assertSame(1, $filas[1]['composiciones'][0]['configuracion']);
    }

    public function test_catalogo_permite_elegir_grados_sin_mediciones_ni_limites_interpretables(): void
    {
        $norma = $this->norma('', 'Consultar nota');
        $resultado = (new CoincidenciasMecanicas0402)->catalogo([$norma, $this->norma()]);
        $this->assertCount(2, $resultado);
        $this->assertSame('Consultar nota', $resultado[0]['cedencia_min']);
        $this->assertStringContainsString('A285', $resultado[0]['norma']);
    }

    private function norma(string $tension = '45–65 [310–450]', string $fluencia = '24 [165] mínimo'): Normas_IM
    {
        $norma = new Normas_IM;
        $norma->idnormas_im = 1;
        $norma->Nombre_Espe = 'ASTM A285/A285M-17';
        $norma->Tablas_Adicionales = ['mecanica' => [['grado' => 'A', 'producto' => 'Placas', 'tension' => $tension, 'fluencia' => $fluencia]]];

        return $norma;
    }

    public function test_incluye_los_limites_y_excluye_valores_fuera_de_intervalo(): void
    {
        $servicio = new CoincidenciasMecanicas0402;
        $this->assertCount(1, $servicio->buscar([$this->norma()], 45, 24));
        $this->assertCount(1, $servicio->buscar([$this->norma()], 65, 40));
        $this->assertSame([], $servicio->buscar([$this->norma()], 65.01, 40));
        $this->assertSame([], $servicio->buscar([$this->norma()], 60, 23.99));
    }

    public function test_permite_varias_normas_y_conserva_sus_condiciones(): void
    {
        $resultado = (new CoincidenciasMecanicas0402)->buscar([$this->norma(), $this->norma('55–75 ksi', '30 ksi')], 61.5, 39.403, 125.6);
        $this->assertCount(2, $resultado);
        $this->assertSame('Placas', $resultado[0]['condiciones']);
        $this->assertNull($resultado[0]['dureza_max']);
    }

    public function test_no_inventa_unidades_ni_interpreta_formulas_o_valores_ausentes(): void
    {
        $servicio = new CoincidenciasMecanicas0402;
        $this->assertSame([], $servicio->buscar([$this->norma()], null, 30));
        $this->assertSame([], $servicio->buscar([$this->norma('45–65', '24')], 60, 30));
        $this->assertSame([], $servicio->buscar([$this->norma('calcular 45 + espesor ksi')], 60, 30));
    }

    public function test_convierte_mpa_para_comparar_con_los_ksi_del_formato(): void
    {
        $resultado = (new CoincidenciasMecanicas0402)->buscar([$this->norma('400–550 MPa', '250 MPa')], 61.5, 39.403);
        $this->assertCount(1, $resultado);
        $this->assertEqualsWithDelta(58.0151, $resultado[0]['tension_min'], 0.001);
    }
}
