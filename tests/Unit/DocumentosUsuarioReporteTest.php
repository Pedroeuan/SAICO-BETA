<?php

namespace Tests\Unit;

use App\Models\Admin\Usuario;
use App\Services\DocumentosUsuarioReporte;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DocumentosUsuarioReporteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'documentos_test', 'database.connections.documentos_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        $schema = \Illuminate\Support\Facades\Schema::connection('documentos_test');
        $schema->create('Formato', function ($table) {
            $table->integer('idFormato')->primary();
            $table->string('Nombre');
            $table->integer('idPrueba');
            $table->integer('idProcedimiento')->nullable();
        });
        $schema->create('procedimiento', function ($table) {
            $table->integer('idProcedimiento')->primary();
            $table->string('Nombre');
        });
        $schema->create('Prueba', function ($table) {
            $table->integer('idPrueba')->primary();
            $table->string('Nombre');
        });
        \Illuminate\Support\Facades\DB::table('Formato')->insert([
            ['idFormato' => 3, 'Nombre' => 'FOR-PIMP-04/03', 'idPrueba' => 1, 'idProcedimiento' => 7],
            ['idFormato' => 2, 'Nombre' => 'FOR-PIMP-04/02', 'idPrueba' => 1, 'idProcedimiento' => 8],
        ]);
        \Illuminate\Support\Facades\DB::table('procedimiento')->insert([
            ['idProcedimiento' => 7, 'Nombre' => 'Procedimiento 7'],
            ['idProcedimiento' => 8, 'Nombre' => 'Procedimiento 8'],
        ]);
        \Illuminate\Support\Facades\DB::table('Prueba')->insert(['idPrueba' => 1, 'Nombre' => 'Servicio del catálogo']);
    }

    private function usuario(): Usuario
    {
        $usuario = new Usuario;
        $usuario->cv_pdf = 'completo.pdf';
        $usuario->documentos_reportes = [
            ['id' => '1', 'tipo' => 'cv_general', 'pdf' => 'general.pdf'],
            ['id' => '2', 'tipo' => 'cv_servicio', 'formato' => 'FOR-PIMP-04/03', 'pdf' => 'servicio.pdf'],
            ['id' => '3', 'tipo' => 'cv_servicio', 'formato' => 'FOR-PIMP-04/02', 'pdf' => 'otro.pdf'],
            ['id' => '4', 'tipo' => 'certificado', 'procedimiento_id' => 7, 'pdf' => 'certificado.pdf'],
            ['id' => '5', 'tipo' => 'certificado', 'procedimiento_id' => 8, 'pdf' => 'otro-certificado.pdf'],
            ['id' => '6', 'tipo' => 'certificado', 'formato' => 'FOR-PIMP-04/02', 'pdf' => 'otro-formato.pdf'],
            ['id' => '7', 'tipo' => 'certificado', 'pdf' => 'comun.pdf'],
        ];

        return $usuario;
    }

    public function test_conserva_cv_completo_en_reportes_anteriores(): void
    {
        $this->assertSame(['completo.pdf'], (new DocumentosUsuarioReporte)->rutas($this->usuario(), null, 'FOR-PIMP-04/03', 7));
    }

    public function test_casillas_desmarcadas_no_anexan_documentos(): void
    {
        $this->assertSame([], (new DocumentosUsuarioReporte)->rutas($this->usuario(), [
            'cv_completo' => '0', 'cv_reporte' => '0', 'certificados' => '0',
        ], 'FOR-PIMP-04/03', 7));
    }

    public function test_filtra_por_formato_y_procedimiento(): void
    {
        $this->assertSame(['servicio.pdf', 'certificado.pdf', 'comun.pdf'], (new DocumentosUsuarioReporte)->rutas($this->usuario(), [
            'cv_reporte' => 1, 'tipo_cv_reporte' => 'servicio', 'certificados' => 1,
        ], 'FOR-PIMP-04/03', 7));
    }

    public function test_0402_utiliza_sus_documentos_sin_incluir_cv_de_0403(): void
    {
        $this->assertSame(['otro.pdf', 'otro-certificado.pdf', 'otro-formato.pdf', 'comun.pdf'], (new DocumentosUsuarioReporte)->rutas($this->usuario(), [
            'cv_reporte' => 1, 'tipo_cv_reporte' => 'servicio', 'certificados' => 1,
        ], 'FOR-PIMP-04/02', 8));
    }

    public function test_vista_compartida_muestra_el_formato_0402(): void
    {
        $html = view('Reportes.IM.partials.documentos-usuario', [
            'errors' => new \Illuminate\Support\ViewErrorBag,
            'formatoDocumentos' => 'FOR-PIMP-04/02',
        ])->render();
        $this->assertStringContainsString('FOR-PIMP-04/02', $html);
        $this->assertStringNotContainsString('FOR-PIMP-04/03', $html);
    }

    public function test_permite_cv_general_y_completo_juntos(): void
    {
        $this->assertSame(['completo.pdf', 'general.pdf'], (new DocumentosUsuarioReporte)->rutas($this->usuario(), [
            'cv_completo' => 1, 'cv_reporte' => 1, 'tipo_cv_reporte' => 'general',
        ], 'FOR-PIMP-04/03', null));
    }

    public function test_no_usa_certificado_de_procedimiento_si_no_hay_procedimiento(): void
    {
        $this->assertSame(['comun.pdf'], (new DocumentosUsuarioReporte)->rutas($this->usuario(), [
            'certificados' => 1,
        ], 'FOR-PIMP-04/03', null));
    }

    public function test_sin_tecnico_no_hay_documentos(): void
    {
        $this->assertSame([], (new DocumentosUsuarioReporte)->rutas(null, null, 'FOR-PIMP-04/03', 7));
    }

    public function test_rechaza_documentos_sin_archivo(): void
    {
        $this->expectException(ValidationException::class);
        (new DocumentosUsuarioReporte)->validar(Request::create('/', 'POST', [
            'documentos_nuevos' => [['tipo' => 'cv_general']],
        ]));
    }

    public function test_formulario_recupera_casillas_guardadas(): void
    {
        $html = view('Reportes.IM.partials.documentos-usuario', ['formatoDocumentos' => 'FOR-PIMP-04/03', 'errors' => new \Illuminate\Support\ViewErrorBag, 'Datos_Equipo' => [
            'Documentos_Usuario' => ['cv_completo' => 0, 'cv_reporte' => 1, 'certificados' => 0, 'tipo_cv_reporte' => 'servicio'],
        ]])->render();

        $this->assertMatchesRegularExpression('/value="1"\s+checked[^>]*>\s*C.V. para reportes/', $html);
        $this->assertMatchesRegularExpression('/value="servicio"\s+selected/', $html);
        $this->assertDoesNotMatchRegularExpression('/value="1"\s+checked[^>]*>\s*C.V. completo/', $html);
    }

    public function test_carga_pdf_y_retira_documento_sin_borrar_cv_completo(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $usuario = new Usuario;
        $id = (string) \Illuminate\Support\Str::uuid();
        $usuario->cv_pdf = 'completo.pdf';
        $usuario->documentos_reportes = [['id' => $id, 'tipo' => 'cv_general', 'pdf' => 'anterior.pdf']];
        \Illuminate\Support\Facades\Storage::disk('public')->put('anterior.pdf', '%PDF-1.4');
        $archivo = \Illuminate\Http\UploadedFile::fake()->createWithContent('cv.pdf', "%PDF-1.4\n1 0 obj\n<<>>\nendobj\n%%EOF");
        $request = Request::create('/', 'POST', [
            'documentos_eliminar' => [$id],
            'documentos_nuevos' => [['tipo' => 'cv_servicio', 'formato_id' => 3]],
        ], [], ['documentos_nuevos' => [['pdf' => $archivo]]]);
        $servicio = new DocumentosUsuarioReporte;
        $servicio->validar($request);
        $servicio->guardar($request, $usuario);

        $this->assertSame('completo.pdf', $usuario->cv_pdf);
        $this->assertCount(1, $usuario->documentos_reportes);
        $this->assertSame('cv_servicio', $usuario->documentos_reportes[0]['tipo']);
        $this->assertSame('C.V. · FOR-PIMP-04/03', $usuario->documentos_reportes[0]['nombre']);
        $this->assertSame('FOR-PIMP-04/03', $usuario->documentos_reportes[0]['formato']);
        $this->assertSame(7, $usuario->documentos_reportes[0]['procedimiento_id']);
        $this->assertSame([$usuario->documentos_reportes[0]['pdf']], $servicio->rutas($usuario, [
            'cv_reporte' => 1, 'tipo_cv_reporte' => 'servicio',
        ], 'FOR-PIMP-04/03', 7));
        $this->assertSame([], $servicio->rutas($usuario, [
            'cv_reporte' => 1, 'tipo_cv_reporte' => 'general',
        ], 'FOR-PIMP-04/03', 7));
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($usuario->documentos_reportes[0]['pdf']);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists('anterior.pdf');
    }

    public function test_cv_por_servicio_requiere_formato(): void
    {
        $archivo = \Illuminate\Http\UploadedFile::fake()->createWithContent('cv.pdf', "%PDF-1.4\n%%EOF");
        $request = Request::create('/', 'POST', [
            'documentos_nuevos' => [['tipo' => 'cv_servicio']],
        ], [], ['documentos_nuevos' => [['pdf' => $archivo]]]);

        $this->expectException(ValidationException::class);
        (new DocumentosUsuarioReporte)->validar($request);
    }

    public function test_apartado_de_usuario_se_renderiza_sin_documentos_previos(): void
    {
        $html = view('Admin.partials.documentos-reportes', [
            'errors' => new \Illuminate\Support\ViewErrorBag,
            'procedimientosDocumentos' => collect(),
            'serviciosDocumentos' => (new DocumentosUsuarioReporte)->catalogo(),
        ])->render();

        $this->assertStringContainsString('Agregar C.V. o certificado', $html);
        $this->assertStringContainsString('Servicio del catálogo · FOR-PIMP-04/03', $html);
        $this->assertStringNotContainsString('[nombre]', $html);
        $this->assertStringNotContainsString('[formato]', $html);
    }

    public function test_no_acepta_servicio_inexistente_ni_procedimiento_de_otro_formato(): void
    {
        foreach ([['formato_id' => 999], ['formato_id' => 3, 'procedimiento_id' => 8]] as $vinculo) {
            $archivo = \Illuminate\Http\UploadedFile::fake()->createWithContent('cv.pdf', "%PDF-1.4\n%%EOF");
            $request = Request::create('/', 'POST', [
                'documentos_nuevos' => [array_merge(['tipo' => 'cv_servicio'], $vinculo)],
            ], [], ['documentos_nuevos' => [['pdf' => $archivo]]]);
            try {
                (new DocumentosUsuarioReporte)->validar($request);
                $this->fail('Debe rechazar el vínculo inválido.');
            } catch (ValidationException $exception) {
                $this->assertNotEmpty($exception->errors());
            }
        }
    }

    public function test_cv_general_no_requiere_servicio_y_se_usa_en_distintos_formatos(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $archivo = \Illuminate\Http\UploadedFile::fake()->createWithContent('cv.pdf', "%PDF-1.4\n%%EOF");
        $request = Request::create('/', 'POST', ['documentos_nuevos' => [['tipo' => 'cv_general']]], [], [
            'documentos_nuevos' => [['pdf' => $archivo]],
        ]);
        $usuario = new Usuario;
        $servicio = new DocumentosUsuarioReporte;
        $servicio->validar($request);
        $servicio->guardar($request, $usuario);
        $this->assertSame('C.V. general para reportes', $usuario->documentos_reportes[0]['nombre']);
        foreach (['FOR-PIMP-04/03', 'FOR-PIMP-04/02'] as $formato) {
            $this->assertSame([$usuario->documentos_reportes[0]['pdf']], $servicio->rutas($usuario, [
                'cv_reporte' => 1, 'tipo_cv_reporte' => 'general',
            ], $formato, 7));
        }
    }
}
