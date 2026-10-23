<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\IM\FOR_PIMP_04_02Controller;
use App\Http\Controllers\Reporte\IM\FOR_PIMP_06_B_01Controller;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QrReporte0402Test extends TestCase
{
    public function test_dompdf_carga_el_svg_del_qr_y_genera_el_pdf(): void
    {
        Storage::fake('public');
        $ruta = 'qr-render-0402.svg';
        $svg = \QrCode::format('svg')->size(300)->margin(0)->generate('https://example.com/qr/reporte/token-prueba');
        Storage::disk('public')->put($ruta, (string) $svg);
        $archivo = Storage::disk('public')->path($ruta);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML('<img src="' . htmlspecialchars($archivo, ENT_QUOTES) . '" width="55" height="55">');
        $dompdf = $pdf->getDomPDF();

        [, $tipo, $error] = \Dompdf\Image\Cache::resolve_url($archivo, '', '', '', $dompdf->getOptions());
        $this->assertSame('svg', $tipo, (string) $error);
        $this->assertNull($error);
        $this->assertStringStartsWith('%PDF-', $pdf->output());
    }

    public function test_resuelve_el_qr_sin_enlace_public_storage(): void
    {
        Storage::fake('public');
        $ruta = 'Reportes/FOR_PIMP_04_02/contrato-prueba/reporte-prueba/QR_REPORTES/qr.svg';
        $svg = \QrCode::format('svg')->size(300)->margin(0)->generate('https://example.com/qr/reporte/token-prueba');
        Storage::disk('public')->put($ruta, (string) $svg);

        $this->assertFileDoesNotExist(public_path('storage/' . $ruta));
        foreach ([FOR_PIMP_04_02Controller::class, FOR_PIMP_06_B_01Controller::class] as $controlador) {
            $resolver = new \ReflectionMethod($controlador, 'resolvePdfPath');
            $archivo = $resolver->invoke(new $controlador, 'storage/' . $ruta);

            $this->assertSame(Storage::disk('public')->path($ruta), $archivo);
            $this->assertStringContainsString('<svg', file_get_contents($archivo));
        }
    }

    public function test_un_qr_inexistente_no_se_incluye_en_el_pdf(): void
    {
        Storage::fake('public');
        foreach ([FOR_PIMP_04_02Controller::class, FOR_PIMP_06_B_01Controller::class] as $controlador) {
            $resolver = new \ReflectionMethod($controlador, 'resolvePdfPath');
            $this->assertNull($resolver->invoke(new $controlador, 'storage/qr-inexistente-0402.svg'));
        }
    }
}
