<?php

namespace Tests\Unit;

use App\Http\Controllers\Reporte\PINS\FOR_PINS_16_01Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Pins1601DocumentosQrTest extends TestCase
{
    public function test_tablas_del_16_no_tratan_metadatos_o_enteros_como_filas(): void
    {
        $fila = ['tipo' => 'fila', 'grupo' => 'sin_titulo', 'data' => ['ID' => '1']];
        $normalizar = [FOR_PINS_16_01Controller::class, 'normalizarBloques16'];
        $this->assertSame([], $normalizar(20));
        $this->assertSame([], $normalizar(['version' => 1, 'cantidad' => 20]));
        $this->assertSame([[$fila]], $normalizar(['version' => 1, 'filas' => [10, $fila, 'texto']]));
        $this->assertSame([[$fila]], $normalizar([$fila]));
        $this->assertSame([[$fila]], $normalizar([[$fila]]));
        $this->assertSame([], $normalizar(['componentes' => [], 'inspeccion' => []]));
    }

    private string $temporal;
    private string $almacenOriginal;

    protected function setUp(): void
    {
        parent::setUp();
        $this->almacenOriginal = storage_path();
        $this->temporal = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'saico-pins16-' . bin2hex(random_bytes(8));
        File::ensureDirectoryExists($this->temporal . '/app/public/cv');
        $this->app->useStoragePath($this->temporal);
        config(['filesystems.default' => 'local', 'filesystems.disks.local.root' => $this->temporal . '/app',
            'filesystems.disks.public.root' => $this->temporal . '/app/public',
            'database.default' => 'pins16_test', 'database.connections.pins16_test' => [
                'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
            ]]);
        Schema::create('users', function ($t) {
            $t->integer('id')->primary(); $t->string('name'); $t->string('cv_pdf'); $t->text('documentos_reportes');
        });
        Schema::create('Reportes', function ($t) {
            $t->integer('idReportes')->primary(); $t->text('Detalles_Generales'); $t->text('Datos_Equipo');
        });
        Schema::create('Firmas_Reportes', function ($t) {
            $t->integer('idFirmas_Reportes')->primary(); $t->integer('idReportes'); $t->text('Firmas');
        });
        Schema::create('Formato', function ($t) {
            $t->integer('idFormato')->primary(); $t->string('Nombre'); $t->integer('idProcedimiento');
        });
        Schema::create('procedimiento', function ($t) {
            $t->integer('idProcedimiento')->primary(); $t->string('Nombre'); $t->string('PDF');
        });
        $this->crearPdf('cv/procedimiento16.pdf', 'PROCEDIMIENTO VISUAL 16');
        $this->crearPdf('cv/procedimiento06.pdf', 'PROCEDIMIENTO INCORRECTO 06');
        DB::table('procedimiento')->insert([
            ['idProcedimiento' => 7, 'Nombre' => 'PRO-PINS-16', 'PDF' => 'cv/procedimiento16.pdf'],
            ['idProcedimiento' => 6, 'Nombre' => 'PRO-PMIP-06', 'PDF' => 'cv/procedimiento06.pdf'],
        ]);
        DB::table('Formato')->insert(['idFormato' => 16, 'Nombre' => 'FOR-PINS-16-01', 'idProcedimiento' => 7]);
        foreach ([1 => 'ALFA', 2 => 'BETA'] as $id => $nombre) {
            $documentos = [];
            foreach (['general', 'servicio', 'certificado', 'otro'] as $tipo) {
                $ruta = "cv/$id-$tipo.pdf";
                $this->crearPdf($ruta, strtoupper($tipo) . ' ' . $nombre);
                $documentos[] = ['id' => "$id-$tipo", 'nombre' => "$tipo $nombre", 'tipo' => match ($tipo) {
                    'general' => 'cv_general', 'certificado' => 'certificado', default => 'cv_servicio',
                }, 'formato' => $tipo === 'general' ? null : ($tipo === 'otro' ? 'FOR-PINS-04-01' : 'FOR-PINS-16-01'),
                    'procedimiento_id' => $tipo === 'general' ? null : 7, 'pdf' => $ruta];
            }
            $this->crearPdf("cv/$id-completo.pdf", "COMPLETO $nombre");
            DB::table('users')->insert(['id' => $id, 'name' => $nombre, 'cv_pdf' => "cv/$id-completo.pdf",
                'documentos_reportes' => json_encode($documentos)]);
        }
    }

    protected function tearDown(): void
    {
        $this->app->useStoragePath($this->almacenOriginal);
        if (str_starts_with($this->temporal, sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'saico-pins16-')) File::deleteDirectory($this->temporal);
        parent::tearDown();
    }

    private function crearPdf(string $ruta, string $texto): void
    {
        $pdf = new \FPDF;
        $pdf->AddPage(); $pdf->SetFont('Helvetica', '', 12); $pdf->Cell(0, 10, $texto);
        $pdf->Output('F', Storage::disk('public')->path($ruta));
    }

    public function test_qr_incluye_procedimiento_visual_del_catalogo_aunque_se_herede_el_06(): void
    {
        $controlador = new FOR_PINS_16_01Controller;
        $parametros = ['Contrato' => 'PRUEBA', 'No_Reporte' => 'PROCEDIMIENTO', 'qr_token' => 'prueba-procedimiento',
            'idProcedimiento' => 6, 'ID_TECNICO' => 1,
            'Documentos_Usuario' => ['cv_reporte' => 1, 'tipo_cv_reporte' => 'servicio']];
        $resultado = $controlador->Datos_QR($parametros);
        $ruta = Storage::disk('public')->path(preg_replace('#^storage/#', '', $resultado['pdf']));
        $texto = (new \Smalot\PdfParser\Parser)->parseFile($ruta)->getText();
        $this->assertStringContainsString('SERVICIO ALFA', $texto);
        $this->assertStringContainsString('PROCEDIMIENTO VISUAL 16', $texto);
        $this->assertStringNotContainsString('PROCEDIMIENTO INCORRECTO 06', $texto);
        $parametros['Documentos_Usuario'] = ['cv_completo' => 0, 'cv_reporte' => 0, 'certificados' => 0];
        $resultado = $controlador->Datos_QR($parametros);
        $texto = (new \Smalot\PdfParser\Parser)->parseFile(Storage::disk('public')->path(preg_replace('#^storage/#', '', $resultado['pdf'])))->getText();
        $this->assertStringContainsString('PROCEDIMIENTO VISUAL 16', $texto);
        $this->assertStringNotContainsString('SERVICIO ALFA', $texto);
    }

    private function llamar(string $metodo, ...$argumentos)
    {
        return (new \ReflectionMethod(FOR_PINS_16_01Controller::class, $metodo))
            ->invoke(new FOR_PINS_16_01Controller, ...$argumentos);
    }

    public function test_reporte_conserva_su_contenido_y_anexa_solo_los_documentos_elegidos(): void
    {
        foreach ([[1, 'servicio', 'SERVICIO ALFA'], [1, 'general', 'GENERAL ALFA'], [2, 'servicio', 'SERVICIO BETA']] as [$id, $tipo, $esperado]) {
            $anexos = $this->llamar('documentosTecnicoReporte16', ['idProcedimiento' => 7], [
                'Documentos_Usuario' => ['cv_reporte' => 1, 'tipo_cv_reporte' => $tipo, 'certificados' => 1],
            ], ['ID_TECNICO' => $id]);
            $pdf = new \setasign\Fpdi\Fpdi;
            $pdf->AddPage(); $pdf->SetFont('Helvetica', '', 12); $pdf->Cell(0, 10, 'REPORTE PRINCIPAL');
            $this->llamar('anexarDocumentosTecnico16', $pdf, $anexos['rutas']);
            $parser = (new \Smalot\PdfParser\Parser)->parseContent($pdf->Output('S'));
            $texto = $parser->getText();
            $this->assertCount(3, $parser->getPages());
            $this->assertStringContainsString('REPORTE PRINCIPAL', $texto);
            $this->assertStringContainsString($esperado, $texto);
            $this->assertStringContainsString($id === 1 ? 'CERTIFICADO ALFA' : 'CERTIFICADO BETA', $texto);
            $this->assertStringNotContainsString('COMPLETO', $texto);
            $this->assertStringNotContainsString('OTRO', $texto);
            $this->assertStringNotContainsString($id === 1 ? 'BETA' : 'ALFA', $texto);
        }
        $sinCv = $this->llamar('documentosTecnicoReporte16', [], ['Documentos_Usuario' => [
            'cv_completo' => 0, 'cv_reporte' => 0, 'certificados' => 0,
        ]], ['ID_TECNICO' => 2]);
        $this->assertSame([], $sinCv['rutas']);
    }

    public function test_cache_se_reutiliza_y_se_invalida_si_cambia_el_pdf_del_tecnico(): void
    {
        $detalles = ['Contrato' => 'PRUEBA', 'No_Reporte' => 'CV', 'idProcedimiento' => 7];
        $equipo = ['Documentos_Usuario' => ['cv_reporte' => 1, 'tipo_cv_reporte' => 'servicio']];
        $firmas = ['ID_TECNICO' => 1, 'numFirmas' => 1];
        $anexos = $this->llamar('documentosTecnicoReporte16', $detalles, $equipo, $firmas);
        $equipo['DOCUMENTOS_QR_FIRMA'] = $anexos['firma'];
        DB::table('Reportes')->insert(['idReportes' => 1, 'Detalles_Generales' => json_encode($detalles), 'Datos_Equipo' => json_encode($equipo)]);
        DB::table('Firmas_Reportes')->insert(['idFirmas_Reportes' => 1, 'idReportes' => 1, 'Firmas' => json_encode($firmas)]);
        $this->llamar('guardarPdfCacheadoReporte16', 1, '%PDF-PRUEBA', $detalles, $equipo);
        $respuesta = $this->llamar('obtenerRespuestaPdfCacheadoReporte16', 1);
        $this->assertNotNull($respuesta);
        $this->assertSame('%PDF-PRUEBA', $respuesta->getContent());
        $controlador = new FOR_PINS_16_01Controller;
        $parametros = ['Contrato' => 'PRUEBA', 'No_Reporte' => 'CV', 'idProcedimiento' => 7,
            'ID_TECNICO' => 1, 'qr_token' => 'prueba-qr-16', 'Documentos_Usuario' => $equipo['Documentos_Usuario']];
        $resultadoQr = $controlador->Datos_QR($parametros);
        $actual = json_decode(DB::table('Reportes')->where('idReportes', 1)->value('Datos_Equipo'), true);
        $actual['PDF_UNIFICADO'] = $resultadoQr['pdf'];
        $actual['QR_PDF'] = $resultadoQr['qr'];
        DB::table('Reportes')->where('idReportes', 1)->update(['Datos_Equipo' => json_encode($actual)]);
        $guardado = json_decode(DB::table('Reportes')->where('idReportes', 1)->value('Datos_Equipo'), true);
        $this->assertNotSame($guardado['PDF_REPORTE_CACHE'], $guardado['PDF_UNIFICADO']);
        $rutaQr = Storage::disk('public')->path(preg_replace('#^storage/#', '', $guardado['PDF_UNIFICADO']));
        $textoQr = (new \Smalot\PdfParser\Parser)->parseFile($rutaQr)->getText();
        $this->assertStringContainsString('SERVICIO ALFA', $textoQr);
        $this->assertStringNotContainsString('REPORTE', $textoQr);
        $this->crearPdf('cv/1-servicio.pdf', 'SERVICIO ACTUALIZADO');
        $controlador->Datos_QR($parametros);
        $this->assertStringContainsString('SERVICIO ACTUALIZADO', (new \Smalot\PdfParser\Parser)->parseFile($rutaQr)->getText());
        $this->assertSame('%PDF-PRUEBA', $this->llamar('obtenerRespuestaPdfCacheadoReporte16', 1)->getContent());
    }

    public function test_la_firma_activa_guarda_el_id_del_tecnico(): void
    {
        $request = \Illuminate\Http\Request::create('/', 'POST', ['numFirmas' => 2]);
        $datos = ['numFirmas' => 2, 'Firmas_Reportes1' => ['ID_TECNICO' => 1], 'Firmas_Reportes2' => ['ID_TECNICO' => 2]];
        $metodo = new \ReflectionMethod(FOR_PINS_16_01Controller::class, 'obtenerPayloadFirmas');
        $resultado = $metodo->invokeArgs(new FOR_PINS_16_01Controller, [$request, &$datos]);
        $this->assertSame(2, json_decode($resultado, true)['ID_TECNICO']);
    }

    public function test_primera_seccion_del_reporte_conserva_los_acentos(): void
    {
        $detalles = array_fill_keys(['Fecha', 'No_Reporte', 'Cliente', 'Contrato', 'Proyecto', 'Orden_Trabajo', 'Lugar', 'Isometrico_Plano'], 'PRUEBA');
        $html = view('Reportes.ReportesPDF.Reporte_FOR_PINS_16_01_01_PDF', [
            'Detalles_Generales' => $detalles, 'Datos_Equipo' => [], 'Grupo_Juntas_Detalles_Re' => [[]],
            'Firmas_Reportes' => [], 'numFirmas' => 0, 'Logo' => '', 'QR_PDF' => null,
        ])->render();
        $this->assertStringContainsString('INSPECCIÓN VISUAL', $html);
        $this->assertStringContainsString('TUBERÍAS DE PROCESO', $html);
        $this->assertStringContainsString('Código:', $html);
        $this->assertStringContainsString('Versión', $html);
        $this->assertStringContainsString('Página', $html);
        $this->assertStringNotContainsString('Ã', $html);
    }

    public function test_convierte_pdf_con_xref_comprimido_aunque_el_temporal_del_entorno_no_exista(): void
    {
        if (!$this->llamar('ghostscriptDocumentosTecnico16')) $this->markTestSkipped('Requiere Ghostscript instalado.');

        // PDF 1.5 con tabla xref en un stream: obliga a usar Ghostscript en lugar de FPDI directo.
        $contenido = 'BT /F1 12 Tf 40 150 Td (CV CON XREF COMPRIMIDO) Tj ET';
        $objetos = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 300 200] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            '<< /Length ' . strlen($contenido) . ">>\nstream\n" . $contenido . "\nendstream",
        ];
        $pdfCrudo = "%PDF-1.5\n";
        $offsets = [0];
        foreach ($objetos as $i => $objeto) {
            $offsets[] = strlen($pdfCrudo);
            $pdfCrudo .= ($i + 1) . " 0 obj\n" . $objeto . "\nendobj\n";
        }
        $offsets[] = strlen($pdfCrudo);
        $xref = chr(0) . pack('Nn', 0, 65535);
        foreach (array_slice($offsets, 1) as $offset) $xref .= chr(1) . pack('Nn', $offset, 0);
        $xref = gzcompress($xref);
        $pdfCrudo .= '6 0 obj << /Type /XRef /Size 7 /Root 1 0 R /W [1 4 2] /Filter /FlateDecode /Length ' . strlen($xref)
            . ">>\nstream\n" . $xref . "\nendstream\nendobj\nstartxref\n" . $offsets[6] . "\n%%EOF";
        Storage::disk('public')->put('cv/comprimido.pdf', $pdfCrudo);
        try {
            (new \setasign\Fpdi\Fpdi)->setSourceFile(Storage::disk('public')->path('cv/comprimido.pdf'));
            $this->fail('La prueba debe utilizar un PDF que obligue a convertir con Ghostscript.');
        } catch (\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException $esperado) {
            $this->assertNotEmpty($esperado->getMessage());
        }
        $anteriores = [];
        foreach (['TEMP', 'TMP', 'TMPDIR'] as $variable) {
            $anteriores[$variable] = getenv($variable);
            putenv($variable . '=' . $this->temporal . '/directorio-inexistente');
        }
        try {
            $pdf = new \setasign\Fpdi\Fpdi;
            $pdf->AddPage(); $pdf->SetFont('Helvetica', '', 12); $pdf->Cell(0, 10, 'REPORTE');
            $this->llamar('anexarDocumentosTecnico16', $pdf, ['cv/comprimido.pdf']);
            $resultado = (new \Smalot\PdfParser\Parser)->parseContent($pdf->Output('S'));
            $this->assertCount(2, $resultado->getPages());
            $this->assertStringContainsString('CV CON XREF COMPRIMIDO', $resultado->getText());
        } finally {
            foreach ($anteriores as $variable => $valor) putenv($valor === false ? $variable : $variable . '=' . $valor);
        }
        $restos = glob(storage_path('app/temp_pdfs/FOR_PINS_16_01/anexos_*')) ?: [];
        $this->assertSame([], $restos, json_encode(array_map(fn ($carpeta) => scandir($carpeta), $restos)));
    }
}
