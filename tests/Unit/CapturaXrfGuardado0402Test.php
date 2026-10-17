<?php

namespace Tests\Unit;

use App\Models\Procesamiento\TrabajoProcesamiento;
use App\Services\Procesamiento\ServicioArchivosProcesamiento;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CapturaXrfGuardado0402Test extends TestCase
{
    public function test_guarda_la_captura_en_el_disco_publico_sin_duplicar_la_carpeta(): void
    {
        Storage::fake('public');
        config(['filesystems.default' => 'public']);
        $archivo = UploadedFile::fake()->create('8.pdf', 1, 'application/pdf');
        $norma = ['Columnas_Seleccionadas' => [3, 4, 5], 'Captura_XRF' => ['data_url' => 'data:image/png;base64,'.base64_encode('captura-verificada')]];
        $metodo = new \ReflectionMethod(\App\Http\Controllers\Reporte\IM\FOR_PIMP_04_02Controller::class, 'guardarArchivosColumnasXrf');
        $metodo->invokeArgs(new \App\Http\Controllers\Reporte\IM\FOR_PIMP_04_02Controller, [$archivo, &$norma, 'contrato', 'reporte']);
        $ruta = preg_replace('#^storage/#', '', $norma['Captura_XRF']['ruta']);
        Storage::disk('public')->assertExists($ruta);
        $this->assertSame('captura-verificada', Storage::disk('public')->get($ruta));
        Storage::disk('public')->assertMissing('public/'.$ruta);
    }

    public function test_retirar_las_normas_no_descarta_el_recorte_de_la_pieza(): void
    {
        $historica = [
            'idnormas_im' => 42,
            'Captura_XRF' => ['ruta' => 'storage/captura.png', 'ancho' => 875, 'alto' => 890],
            'Columnas_Seleccionadas' => [3, 4, 5],
            'Analisis_PDF' => [['ruta' => 'storage/original.pdf']],
            'Resultados_XRF' => ['C' => ['promedio' => '0.1297']],
            'Tabla' => [['Elemento' => 'C', 'Composicion' => '0.30 max.']],
        ];
        $metodo = new \ReflectionMethod(\App\Http\Controllers\Reporte\IM\FOR_PIMP_04_02Controller::class, 'construirNormaIM');
        $resultado = $metodo->invoke(new \App\Http\Controllers\Reporte\IM\FOR_PIMP_04_02Controller,
            \Illuminate\Http\Request::create('/', 'POST'), $historica);
        foreach (['Captura_XRF', 'Columnas_Seleccionadas', 'Analisis_PDF', 'Resultados_XRF'] as $campo) {
            $this->assertSame($historica[$campo], $resultado[$campo]);
        }
        $this->assertSame([], $resultado['Tabla']);
    }

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
