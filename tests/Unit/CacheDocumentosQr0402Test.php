<?php

namespace Tests\Unit;

use App\Services\CacheDocumentosQr0402;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CacheDocumentosQr0402Test extends TestCase
{
    public function test_reutiliza_anexos_y_detecta_si_se_sustituyen_los_archivos(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $disco = Storage::disk('public');
        $disco->put('certificado.pdf', 'original');
        $disco->put('qr.svg', 'qr');
        $disco->put('unificado.pdf', '%PDF-unificado');
        $servicio = new CacheDocumentosQr0402;
        $parametros = ['equipo' => 1, 'tecnico' => 2];
        $entradas = [$disco->path('certificado.pdf')];
        $firma = $servicio->firma($parametros, $entradas);
        $resultado = ['pdf' => 'storage/unificado.pdf', 'qr' => 'storage/qr.svg'];
        $servicio->guardar($firma, $resultado);
        $this->assertSame($resultado, $servicio->obtener($firma));
        $disco->put('certificado.pdf', 'cambiado');
        $this->assertNotSame($firma, $servicio->firma($parametros, $entradas));
        $this->assertNotSame($firma, $servicio->firma(['equipo' => 2], $entradas));
        $disco->put('unificado.pdf', '%PDF-otra-version');
        $this->assertNull($servicio->obtener($firma));
        $disco->delete('qr.svg');
        $this->assertNull($servicio->obtener($firma));
    }
}
