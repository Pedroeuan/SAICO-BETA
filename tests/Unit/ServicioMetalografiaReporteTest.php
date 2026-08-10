<?php

namespace Tests\Unit;

use App\Services\ServicioMetalografiaReporte;
use PHPUnit\Framework\TestCase;

/** Pruebas puras: no usan modelos, conexiones ni escrituras en la base de datos. */
class ServicioMetalografiaReporteTest extends TestCase
{
    private ServicioMetalografiaReporte $servicio;

    protected function setUp(): void
    {
        parent::setUp();
        $this->servicio = new ServicioMetalografiaReporte();
    }

    /** El servidor debe ignorar los totales manipulados por el navegador y recalcularlos. */
    public function test_recalcula_cruces_suma_y_promedio_desde_los_marcadores(): void
    {
        $conteo = $this->servicio->normalizarConteoGranos(json_encode([
            'resumen' => ['suma' => 999, 'promedio' => 999],
            'lineas' => [
                [
                    'id' => 4,
                    'x1' => 0.1,
                    'y1' => 0.2,
                    'x2' => 0.9,
                    'y2' => 0.2,
                    'marcadores' => [0.75, 0.25, 0.5, 0.5],
                    'conteo' => 999,
                ],
                [
                    'id' => 8,
                    'x1' => 0.1,
                    'y1' => 0.7,
                    'x2' => 0.9,
                    'y2' => 0.7,
                    'marcadores' => [0.2, 0.4, 0.6, 0.8],
                ],
            ],
        ], JSON_THROW_ON_ERROR));

        $this->assertSame([0.25, 0.5, 0.75], $conteo['lineas'][0]['marcadores']);
        $this->assertSame(3, $conteo['lineas'][0]['cruces']);
        $this->assertSame(2, $conteo['lineas'][0]['granos_completos']);
        $this->assertSame(3.0, $conteo['lineas'][0]['conteo']);
        $this->assertSame(7.0, $conteo['resumen']['suma']);
        $this->assertSame(3.5, $conteo['resumen']['promedio']);
    }

    /** Entradas corruptas, líneas sin longitud y marcadores en los extremos no deben contaminar el reporte. */
    public function test_descarta_datos_invalidos_y_limita_coordenadas(): void
    {
        $this->assertNull($this->servicio->normalizarConteoGranos('{json-invalido'));

        $conteo = $this->servicio->normalizarConteoGranos(json_encode([
            'lineas' => [
                ['x1' => 0.5, 'y1' => 0.5, 'x2' => 0.5, 'y2' => 0.5, 'marcadores' => [0.3]],
                ['x1' => -5, 'y1' => 0.4, 'x2' => 8, 'y2' => 0.4, 'marcadores' => [0, 0.02, 0.3, 0.98, 1]],
            ],
        ], JSON_THROW_ON_ERROR));

        $this->assertCount(1, $conteo['lineas']);
        $this->assertEquals(0.0, $conteo['lineas'][0]['x1']);
        $this->assertEquals(1.0, $conteo['lineas'][0]['x2']);
        $this->assertSame([0.3], $conteo['lineas'][0]['marcadores']);
        $this->assertSame(1.0, $conteo['resumen']['suma']);
    }

    /** Imagen y resultados tienen posiciones independientes y valores inválidos vuelven a opciones seguras. */
    public function test_normaliza_el_layout_del_analisis(): void
    {
        $layout = $this->servicio->normalizarLayoutAnalisis([
            'imagen' => ['pagina' => 0, 'posicion' => 'fuera_de_hoja'],
            'resultados' => ['pagina' => 3, 'posicion' => 'abajo_derecha'],
        ]);

        $this->assertSame(['pagina' => 1, 'posicion' => 'arriba_izquierda'], $layout['imagen']);
        $this->assertSame(['pagina' => 3, 'posicion' => 'abajo_derecha'], $layout['resultados']);
    }

    /** El respaldo del PDF debe contener el resumen mínimo acordado y los valores de Fiji. */
    public function test_construye_descripcion_metalografica_con_fases_y_tamano_de_grano(): void
    {
        $texto = $this->servicio->construirTextoResultados([
            'porcentaje_perlita' => 35.271,
            'porcentaje_ferrita' => 64.729,
            'archivo_original' => 'muestra.jpg',
            'umbral_minimo' => 10,
            'umbral_maximo' => 90,
        ], [], [
            'FASES_PRESENTES' => 'Ferrita + perlita',
        ], [
            'valor_grano' => '10',
        ]);

        $this->assertStringContainsString('Fases presentes: Ferrita + perlita', $texto);
        $this->assertStringContainsString('Perlita / zonas oscuras: 35.271 %', $texto);
        $this->assertStringContainsString('Ferrita / zonas claras: 64.729 %', $texto);
        $this->assertStringContainsString('Método de tamaño de grano ASTM E112: Comparativo', $texto);
        $this->assertStringContainsString('Tamaño de grano: 10', $texto);
        $this->assertStringContainsString('Analizador: Fiji', $texto);
    }
}
