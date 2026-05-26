<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publicacion_metricas_historial', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('publicacion_id');
            $table->date('fecha_corte');
            $table->unsignedInteger('reacciones')->default(0);
            $table->unsignedInteger('comentarios')->default(0);
            $table->unsignedInteger('compartidos')->default(0);
            $table->unsignedInteger('alcance')->default(0);
            $table->unsignedInteger('impresiones')->default(0);
            $table->unsignedInteger('clicks')->default(0);
            $table->decimal('engagement', 8, 2)->default(0);
            $table->json('detalle_json')->nullable();
            $table->timestamps();

            $table->unique(['publicacion_id', 'fecha_corte'], 'publicacion_metricas_unica_fecha');
            $table->index(['fecha_corte', 'alcance'], 'publicacion_metricas_fecha_alcance_idx');
            $table->foreign('publicacion_id')
                ->references('id')
                ->on('publicaciones')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publicacion_metricas_historial');
    }
};
