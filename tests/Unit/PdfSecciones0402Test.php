<?php

namespace Tests\Unit;

use App\Services\PdfSecciones0402;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PdfSecciones0402Test extends TestCase
{
    public function test_un_cambio_de_texto_regenera_solo_la_seccion_modificada(): void
    {
        Storage::fake('local');
        $servicio = new PdfSecciones0402;
        $generaciones = 0;
        $render = function () use (&$generaciones) { return '%PDF-'.++$generaciones; };
        $this->assertSame('%PDF-1', $servicio->obtener(1, 'datos', 'Texto inicial', $render));
        $this->assertSame('%PDF-2', $servicio->obtener(1, 'fotos', 'Micrografia y comentario', $render));
        $this->assertSame('%PDF-3', $servicio->obtener(1, 'datos', 'Texto corregido', $render));
        $this->assertSame('%PDF-2', $servicio->obtener(1, 'fotos', 'Micrografia y comentario', $render));
        $this->assertSame(3, $generaciones);
        $this->assertCount(2, Storage::disk('local')->files('reportes_generados/04_02/1/secciones'));
    }

    public function test_detecta_otra_imagen_con_el_mismo_nombre_tamano_y_fecha_sin_alterarla(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('imagen.png', 'original');
        $ruta = Storage::disk('local')->path('imagen.png');
        $fecha = filemtime($ruta);
        $html = '<img src="'.$ruta.'">';
        $servicio = new PdfSecciones0402;
        $this->assertSame('%PDF-original', $servicio->obtener(1, 'fotos', $html, fn () => '%PDF-original'));
        file_put_contents($ruta, 'cambiada');
        touch($ruta, $fecha);
        $this->assertSame('%PDF-cambiada', $servicio->obtener(1, 'fotos', $html, fn () => '%PDF-cambiada'));
        $this->assertSame('cambiada', file_get_contents($ruta));
    }

    public function test_si_falla_la_generacion_conserva_la_version_anterior(): void
    {
        Storage::fake('local');
        $servicio = new PdfSecciones0402;
        $servicio->obtener(1, 'datos', 'Original', fn () => '%PDF-original');
        try {
            $servicio->obtener(1, 'datos', 'Editado', fn () => throw new \RuntimeException('Fallo de render'));
            $this->fail('Debio fallar');
        } catch (\RuntimeException $error) {
            $this->assertSame('Fallo de render', $error->getMessage());
        }
        $this->assertSame('%PDF-original', $servicio->obtener(1, 'datos', 'Original', fn () => throw new \RuntimeException('No debe renderizar')));
    }
}
