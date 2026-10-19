<?php

namespace Tests\Unit;

use App\Services\ServicioAnalisisImagenImageJ;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AnalisisImagenHistorico0402Test extends TestCase
{
    public function test_conserva_el_analisis_del_reporte_aunque_edite_otro_usuario_y_falte_el_json(): void
    {
        Storage::fake('public');
        $historico = ['token' => '11111111-1111-4111-8111-111111111111', 'usuario_id' => 7,
            'porcentaje_perlita' => 32.708, 'porcentaje_ferrita' => 67.292,
            'rutas' => ['original' => 'storage/imagen.png']];
        $this->assertSame($historico, app(ServicioAnalisisImagenImageJ::class)->resolverParaReporte($historico['token'], 99, $historico));
    }

    public function test_un_token_nuevo_inexistente_devuelve_validacion_y_no_un_error_500(): void
    {
        Storage::fake('public');
        $this->expectException(ValidationException::class);
        app(ServicioAnalisisImagenImageJ::class)->resolverParaReporte('22222222-2222-4222-8222-222222222222', 99,
            ['token' => '11111111-1111-4111-8111-111111111111']);
    }

    public function test_un_analisis_nuevo_debe_pertenecer_al_usuario_actual(): void
    {
        Storage::fake('public');
        $token = '22222222-2222-4222-8222-222222222222';
        Storage::disk('public')->put("Reportes/Analisis_Imagen/7/{$token}/resultado.json", json_encode(['usuario_id' => 7, 'token' => $token]));
        $this->expectException(ValidationException::class);
        app(ServicioAnalisisImagenImageJ::class)->resolverParaReporte($token, 99);
    }
}
