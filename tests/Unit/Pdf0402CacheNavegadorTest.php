<?php

namespace Tests\Unit;

use App\Http\Controllers\Procesamiento\ProcesamientoPdfController;
use App\Services\ServicioPdfGenerado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Pdf0402CacheNavegadorTest extends TestCase
{
    public function test_pdf_vigente_0402_no_permite_reutilizar_cache_del_navegador(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('reporte.pdf', '%PDF-actualizado');
        $servicio = \Mockery::mock(ServicioPdfGenerado::class);
        $servicio->shouldReceive('rutaVigente')->once()->with(1, '04_02', 'es')->andReturn(Storage::disk('local')->path('reporte.pdf'));
        $respuesta = (new ProcesamientoPdfController)->pagina(Request::create('/pdf'), 1, '04_02', $servicio);
        $this->assertTrue($respuesta->headers->hasCacheControlDirective('no-store'));
        $this->assertTrue($respuesta->headers->hasCacheControlDirective('private'));
        $this->assertSame('no-cache', $respuesta->headers->get('Pragma'));
        $this->assertSame('%PDF-actualizado', file_get_contents($respuesta->getFile()->getPathname()));
    }

    public function test_no_cambia_las_cabeceras_de_otros_formatos(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('reporte.pdf', '%PDF');
        $servicio = \Mockery::mock(ServicioPdfGenerado::class);
        $servicio->shouldReceive('rutaVigente')->once()->with(1, '06_B_01', 'es')->andReturn(Storage::disk('local')->path('reporte.pdf'));
        $respuesta = (new ProcesamientoPdfController)->pagina(Request::create('/pdf'), 1, '06_B_01', $servicio);
        $this->assertFalse($respuesta->headers->hasCacheControlDirective('no-store'));
    }
}
