<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Historial de comentarios de revisión y de ventas para cada OC.
     * Cada comentario se guarda como un registro independiente.
     */
    public function up(): void
    {
        if (!Schema::hasTable('OC_Comentarios')) {
            Schema::create('OC_Comentarios', function (Blueprint $table) {
                $table->bigIncrements('idComentario');
                $table->integer('idOC');
                $table->string('tipo', 100)->nullable(); // revision | ventas
                $table->string('revision', 20)->nullable();
                $table->longText('comentario')->nullable();
                $table->string('autor', 255)->nullable();
                $table->bigInteger('idUsers');
                $table->timestamps();

                $table->index('idOC', 'idx_oc_comentarios_idoc');
                $table->index('idUsers', 'idx_oc_comentarios_idusers');
                $table->index('tipo', 'idx_oc_comentarios_tipo');

                $table->foreign('idOC', 'fk_oc_comentarios_oc')
                    ->references('idOC')->on('OC');
                $table->foreign('idUsers', 'fk_oc_comentarios_users')
                    ->references('id')->on('users');
            });
        }

        $this->backfillComentariosExistentes();
    }

    public function down(): void
    {
        Schema::dropIfExists('OC_Comentarios');
    }

    /**
     * Pasa los comentarios que antes vivían en columnas de OC al historial.
     */
    private function backfillComentariosExistentes(): void
    {
        if (!Schema::hasTable('OC') || !Schema::hasTable('OC_Comentarios')) {
            return;
        }

        $configuraciones = [
            [
                'tipo' => 'revision',
                'campo' => 'Revision_OC',
                'autor' => 'Revision_Autor',
                'fecha' => 'Revision_At',
            ],
            [
                'tipo' => 'ventas',
                'campo' => 'Comentario_Ventas',
                'autor' => 'Comentario_Ventas_Autor',
                'fecha' => 'Comentario_Ventas_At',
            ],
        ];

        foreach (DB::table('OC')->get() as $OC) {
            foreach ($configuraciones as $config) {
                $texto = trim((string) ($OC->{$config['campo']} ?? ''));

                if ($texto === '') {
                    continue;
                }

                $existe = DB::table('OC_Comentarios')
                    ->where('idOC', $OC->idOC)
                    ->where('tipo', $config['tipo'])
                    ->exists();

                if ($existe) {
                    continue;
                }

                $fecha = ($OC->{$config['fecha']} ?? null) ?: now();
                $revision = $OC->Revision ?? null;

                if (empty($revision) && preg_match('/V\s*(\d+)/i', (string) ($OC->Estatus ?? ''), $coincidencias)) {
                    $revision = 'V' . (int) $coincidencias[1];
                }

                DB::table('OC_Comentarios')->insert([
                    'idOC' => $OC->idOC,
                    'tipo' => $config['tipo'],
                    'revision' => $revision ?: null,
                    'comentario' => $texto,
                    'autor' => ($OC->{$config['autor']} ?? null) ?: null,
                    'idUsers' => $this->userIdDesdeAutor($OC->{$config['autor']} ?? null),
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ]);
            }
        }
    }

    /**
     * Busca el usuario por nombre para respetar la llave foránea de idUsers.
     */
    private function userIdDesdeAutor(?string $autor): int
    {
        if (!empty($autor)) {
            $id = DB::table('users')->where('name', $autor)->value('id');

            if ($id) {
                return (int) $id;
            }
        }

        return (int) (DB::table('users')->min('id') ?? 1);
    }
};
