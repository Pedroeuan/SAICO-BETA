<?php

namespace Tests\Unit;

use App\Http\Controllers\Normas_IM\NormasIMController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class NormasIMAltaRapidaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');
        DB::statement('CREATE TABLE Normas_IM (idnormas_im INTEGER PRIMARY KEY AUTOINCREMENT, Nombre_Espe TEXT, Variable TEXT, Tabla TEXT, Observaciones TEXT)');
    }

    public function test_guarda_la_norma_y_devuelve_la_tabla_para_seleccionarla(): void
    {
        $response = (new NormasIMController)->storeRapida(Request::create('/', 'POST', [
            'NombreESP' => 'ASTM A500',
            'Variable' => 'Gr. B',
            'Elemento' => ['C', 'Mn', 'Fe'],
            'Composicion' => ['0.30 máx.', '1.40 máx.', null],
        ]));

        $this->assertSame(201, $response->getStatusCode());
        $norma = $response->getData(true)['norma'];
        $this->assertSame('ASTM A500', $norma['Nombre_Espe']);
        $this->assertSame('Gr. B', $norma['Variable']);
        $this->assertSame([
            ['Elemento' => 'C', 'Composicion' => '0.30 máx.'],
            ['Elemento' => 'Mn', 'Composicion' => '1.40 máx.'],
            ['Elemento' => 'Fe', 'Composicion' => ''],
        ], $norma['Tabla']);
        $guardada = DB::table('Normas_IM')->where('idnormas_im', $norma['idnormas_im'])->first();
        $this->assertSame($norma['Tabla'], json_decode($guardada->Tabla, true));
    }

    public function test_rechaza_composiciones_que_no_corresponden_a_los_elementos(): void
    {
        try {
            (new NormasIMController)->storeRapida(Request::create('/', 'POST', [
                'NombreESP' => 'ASTM A500',
                'Elemento' => ['C', 'Mn'],
                'Composicion' => ['0.30 máx.'],
            ]));
            $this->fail('La solicitud debe rechazarse.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('Composicion', $exception->errors());
            $this->assertSame(0, DB::table('Normas_IM')->count());
        }
    }
}
