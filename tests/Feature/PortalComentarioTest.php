<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PortalComentarioTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('Reportes');
        Schema::create('Reportes', function (Blueprint $table) {
            $table->id('idReportes');
            $table->unsignedBigInteger('idPrueba_Aplica')->nullable();
            $table->json('Detalles_Generales')->nullable();
            $table->string('Estatus')->nullable();
            $table->text('comentarios')->nullable();
        });
    }

    public function test_un_usuario_autenticado_puede_guardar_un_comentario_en_el_reporte()
    {
        $user = User::factory()->create();

        $reporteId = DB::table('Reportes')->insertGetId([
            'idPrueba_Aplica' => 1,
            'Detalles_Generales' => json_encode(['Proyecto' => 'Proyecto de prueba']),
            'Estatus' => 'Activo',
            'comentarios' => null,
        ]);

        $response = $this->actingAs($user)
            ->postJson('/portal/reporte/' . $reporteId . '/comentario', [
                'comentario' => 'Comentario del cliente',
            ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('comentario', 'Comentario del cliente');

        $this->assertDatabaseHas('Reportes', [
            'idReportes' => $reporteId,
            'comentarios' => 'Comentario del cliente',
        ]);
    }
}
