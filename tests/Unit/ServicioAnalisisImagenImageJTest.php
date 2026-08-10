<?php

namespace Tests\Unit;

use App\Services\ServicioAnalisisImagenImageJ;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** Integración local con Fiji usando archivos sintéticos, sin acceder a la base de datos. */
class ServicioAnalisisImagenImageJTest extends TestCase
{
    private array $directoriosGenerados = [];

    protected function tearDown(): void
    {
        // Cada evidencia creada por la prueba se elimina para no dejar datos falsos en storage.
        foreach ($this->directoriosGenerados as $directorio) {
            Storage::disk('public')->deleteDirectory($directorio);
        }
        parent::tearDown();
    }

    /** Comprueba que Fiji mida 30% oscuro y que la fase clara se obtenga como complemento exacto. */
    public function test_fiji_mide_una_imagen_de_porcentaje_conocido(): void
    {
        $ruta = $this->crearImagenBifasica(100, 100, 30);
        $archivo = new UploadedFile($ruta, 'micrografia-sintetica.png', 'image/png', null, true);

        $resultado = app(ServicioAnalisisImagenImageJ::class)->procesarFraccionFases(
            $archivo,
            0,
            85,
            'perlita',
            987654321
        );

        $this->directoriosGenerados[] = "Reportes/Analisis_Imagen/987654321/{$resultado['token']}";
        $this->assertEqualsWithDelta(30.0, $resultado['porcentaje_perlita'], 0.001);
        $this->assertEqualsWithDelta(70.0, $resultado['porcentaje_ferrita'], 0.001);
        $this->assertSame(100, $resultado['ancho']);
        $this->assertSame(100, $resultado['alto']);
        $this->assertTrue(Storage::disk('public')->exists(
            "Reportes/Analisis_Imagen/987654321/{$resultado['token']}/imagen-visual.png"
        ));
        $this->assertTrue(Storage::disk('public')->exists(
            "Reportes/Analisis_Imagen/987654321/{$resultado['token']}/imagen-binaria.png"
        ));
        // Las evidencias deben resolverse en el host actual, sin depender del APP_URL del worker.
        $this->assertSame(
            "/storage/Reportes/Analisis_Imagen/987654321/{$resultado['token']}/imagen-visual.png",
            $resultado['urls']['imagen_visual']
        );
        $this->assertSame(
            "/storage/Reportes/Analisis_Imagen/987654321/{$resultado['token']}/imagen-binaria.png",
            $resultado['urls']['imagen_binaria']
        );
    }

    /** El histograma oficial debe conservar exactamente los 3,000 píxeles oscuros y 7,000 claros. */
    public function test_histograma_de_fiji_coincide_con_los_pixeles_sinteticos(): void
    {
        $ruta = $this->crearImagenBifasica(100, 100, 30);
        $archivo = new UploadedFile($ruta, 'histograma-sintetico.png', 'image/png', null, true);

        $resultado = app(ServicioAnalisisImagenImageJ::class)->obtenerHistograma8Bit($archivo);

        $this->assertSame(10000, array_sum($resultado['histograma']));
        $this->assertSame(3000, array_sum(array_slice($resultado['histograma'], 0, 86)));
        $this->assertSame(7000, array_sum(array_slice($resultado['histograma'], 86)));
    }

    /** Genera una imagen sin compresión con una división vertical de intensidad 40/220. */
    private function crearImagenBifasica(int $ancho, int $alto, int $porcentajeOscuro): string
    {
        $ruta = tempnam(sys_get_temp_dir(), 'saico-imagej-') . '.png';
        $imagen = imagecreatetruecolor($ancho, $alto);
        $oscuro = imagecolorallocate($imagen, 40, 40, 40);
        $claro = imagecolorallocate($imagen, 220, 220, 220);
        $corte = (int) round($ancho * $porcentajeOscuro / 100);
        imagefilledrectangle($imagen, 0, 0, $corte - 1, $alto - 1, $oscuro);
        imagefilledrectangle($imagen, $corte, 0, $ancho - 1, $alto - 1, $claro);
        imagepng($imagen, $ruta, 0);
        imagedestroy($imagen);

        return $ruta;
    }
}
