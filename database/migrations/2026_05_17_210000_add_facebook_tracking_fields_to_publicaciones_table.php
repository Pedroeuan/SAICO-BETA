<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publicaciones', function (Blueprint $table): void {
            $table->string('origen_publicacion', 30)->default('sistema')->after('programado_at');
            $table->string('facebook_post_id', 120)->nullable()->after('origen_publicacion');
            $table->string('facebook_page_id', 80)->nullable()->after('facebook_post_id');
            $table->string('facebook_permalink', 500)->nullable()->after('facebook_page_id');
            $table->timestamp('facebook_created_at')->nullable()->after('facebook_permalink');
            $table->timestamp('sincronizado_metricas_at')->nullable()->after('facebook_created_at');
            $table->string('estado_sync_metricas', 20)->nullable()->after('sincronizado_metricas_at');
            $table->json('metricas_facebook_json')->nullable()->after('estado_sync_metricas');
            $table->json('ultima_respuesta_api')->nullable()->after('metricas_facebook_json');

            $table->unique('facebook_post_id', 'publicaciones_facebook_post_id_unique');
            $table->index(['origen_publicacion', 'created_at'], 'publicaciones_origen_created_idx');
            $table->index(['estado_sync_metricas', 'sincronizado_metricas_at'], 'publicaciones_sync_metricas_idx');
        });
    }

    public function down(): void
    {
        Schema::table('publicaciones', function (Blueprint $table): void {
            $table->dropIndex('publicaciones_origen_created_idx');
            $table->dropIndex('publicaciones_sync_metricas_idx');
            $table->dropUnique('publicaciones_facebook_post_id_unique');
            $table->dropColumn([
                'origen_publicacion',
                'facebook_post_id',
                'facebook_page_id',
                'facebook_permalink',
                'facebook_created_at',
                'sincronizado_metricas_at',
                'estado_sync_metricas',
                'metricas_facebook_json',
                'ultima_respuesta_api',
            ]);
        });
    }
};
