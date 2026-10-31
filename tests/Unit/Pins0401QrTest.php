<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\PINS\FOR_PINS_04_01Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/** Genera anexos reales en un directorio temporal y una base SQLite aislada. */
class Pins0401QrTest extends TestCase
{
    public function test_el_qr_cambia_de_cv_y_tecnico_sin_conservar_el_documento_anterior(): void
    {
        config(['database.default' => 'pins04_test', 'database.connections.pins04_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        Schema::create('users', function ($t) {
            $t->integer('id')->primary(); $t->string('cv_pdf'); $t->text('documentos_reportes');
        });
        Schema::create('general_eyc', function ($t) { $t->integer('idGeneral_EyC'); $t->string('Factura'); });
        Schema::create('certificados', function ($t) { $t->integer('idGeneral_EyC'); $t->string('Certificado_Actual'); });
        Schema::create('procedimiento', function ($t) { $t->integer('idProcedimiento'); $t->string('PDF')->nullable(); });
        DB::table('procedimiento')->insert(['idProcedimiento' => 7, 'PDF' => null]);
        $anterior = storage_path();
        $temporal = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'saico-pins04-' . bin2hex(random_bytes(8));
        File::ensureDirectoryExists($temporal . '/app/public/cv');
        $this->app->useStoragePath($temporal);
        try {
            foreach ([1 => 'ALFA', 2 => 'BETA'] as $id => $nombre) {
                $documentos = [];
                foreach (['completo', 'general', 'servicio', 'otro'] as $tipo) {
                    $ruta = "cv/$id-$tipo.pdf";
                    $pdf = new \FPDF;
                    $pdf->AddPage(); $pdf->SetFont('Helvetica', '', 12);
                    $pdf->Cell(0, 10, strtoupper($tipo) . ' ' . $nombre);
                    $pdf->Output('F', storage_path('app/public/' . $ruta));
                    if ($tipo !== 'completo') $documentos[] = ['id' => "$id-$tipo", 'tipo' => $tipo === 'general' ? 'cv_general' : 'cv_servicio',
                        'formato' => $tipo === 'general' ? null : ($tipo === 'servicio' ? 'FOR-PINS-04-01' : 'FOR-PINS-03-02'),
                        'procedimiento_id' => $tipo === 'general' ? null : 7, 'pdf' => $ruta];
                }
                DB::table('users')->insert(['id' => $id, 'cv_pdf' => "cv/$id-completo.pdf", 'documentos_reportes' => json_encode($documentos)]);
            }
            $controlador = new FOR_PINS_04_01Controller;
            $parametros = ['Contrato' => 'PRUEBA', 'No_Reporte' => 'CV', 'qr_token' => 'prueba-pins04',
                'idPenetrante' => null, 'idRemovedor' => null, 'idRevelador' => null, 'idProcedimiento' => 7];
            foreach ([[1, 'servicio', 'SERVICIO ALFA'], [1, 'general', 'GENERAL ALFA'], [2, 'servicio', 'SERVICIO BETA']] as [$id, $tipo, $esperado]) {
                $resultado = $controlador->Datos_QR($parametros + ['ID_TECNICO' => $id,
                    'Documentos_Usuario' => ['cv_completo' => 0, 'cv_reporte' => 1, 'certificados' => 0, 'tipo_cv_reporte' => $tipo]]);
                $ruta = storage_path('app/public/' . preg_replace('#^storage/#', '', $resultado['pdf']));
                $texto = (new \Smalot\PdfParser\Parser)->parseFile($ruta)->getText();
                $this->assertStringContainsString($esperado, $texto);
                $this->assertStringNotContainsString('COMPLETO', $texto);
                $this->assertStringNotContainsString('OTRO', $texto);
                $this->assertStringNotContainsString($id === 1 ? 'BETA' : 'ALFA', $texto);
                $png = app(\App\Services\QrParaPdf::class)->rutaParaDompdf($resultado['qr']);
                $this->assertNotNull($png);
                $this->assertSame(IMAGETYPE_PNG, getimagesize($png)[2]);
            }
            $sinCv = $controlador->Datos_QR($parametros + ['ID_TECNICO' => 2,
                'Documentos_Usuario' => ['cv_completo' => 0, 'cv_reporte' => 0, 'certificados' => 0]]);
            $this->assertNull($sinCv['pdf']);
        } finally {
            $this->app->useStoragePath($anterior);
            if (str_starts_with($temporal, sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'saico-pins04-')) File::deleteDirectory($temporal);
        }
    }
}
