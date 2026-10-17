<?php

namespace Tests\Unit;

use App\Models\Procesamiento\TrabajoProcesamiento;
use App\Services\Procesamiento\ServicioArchivosProcesamiento;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CapturaXrfGuardado0402Test extends TestCase
{
    public function test_reutiliza_solo_la_captura_del_mismo_archivo_y_disparos(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('entrada.pdf', '%PDF-original');
        Storage::disk('local')->put('otro.pdf', '%PDF-otro');
        $archivo = new UploadedFile(Storage::disk('local')->path('entrada.pdf'), '8.pdf', 'application/pdf', null, true);
        $otro = new UploadedFile(Storage::disk('local')->path('otro.pdf'), '8.pdf', 'application/pdf', null, true);
        $captura = ['columnas' => [7, 8, 11], 'data_url' => 'data:image/png;base64,imagen', 'ancho' => 875, 'alto' => 890];
        $trabajo = new TrabajoProcesamiento([
            'tipo' => 'xrf_columnas', 'estado' => 'completado',
            'contexto' => ['entradas' => [['ruta' => 'entrada.pdf']]],
            'resultado' => json_encode(['columnas_seleccionadas' => [7, 8, 11], 'captura' => $captura]),
            'expira_at' => now()->addDay(),
        ]);
        $servicio = new ServicioArchivosProcesamiento;
        $this->assertSame($captura, $servicio->capturaVerificada($trabajo, $archivo, [7, 8, 11]));
        $this->assertNull($servicio->capturaVerificada($trabajo, $otro, [7, 8, 11]));
        $this->assertNull($servicio->capturaVerificada($trabajo, $archivo, [11, 8, 7]));
        $trabajo->expira_at = now()->subMinute();
        $this->assertNull($servicio->capturaVerificada($trabajo, $archivo, [7, 8, 11]));
        $trabajo->expira_at = now()->addDay();
        $trabajo->estado = 'procesando';
        $this->assertNull($servicio->capturaVerificada($trabajo, $archivo, [7, 8, 11]));
    }
}
