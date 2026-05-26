<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encuestas_satisfaccion_vehicular', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salida_vehiculo_id')->constrained('salidas_vehiculos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('origen_respuesta', ['solicitante', 'chofer', 'operativo'])->default('solicitante');
            $table->unsignedTinyInteger('calificacion_servicio');
            $table->unsignedTinyInteger('calificacion_estado_unidad');
            $table->unsignedTinyInteger('calificacion_tiempo_respuesta');
            $table->unsignedTinyInteger('nps')->nullable();
            $table->enum('sentimiento', ['positivo', 'neutro', 'negativo'])->default('neutro');
            $table->text('comentario')->nullable();
            $table->date('fecha_encuesta');
            $table->timestamp('respondida_en')->nullable();
            $table->timestamps();

            $table->unique(['salida_vehiculo_id', 'user_id'], 'uniq_encuesta_salida_usuario');
            $table->index(['vehiculo_id', 'fecha_encuesta'], 'idx_encuesta_vehiculo_fecha');
            $table->index(['sentimiento', 'fecha_encuesta'], 'idx_encuesta_sentimiento_fecha');
            $table->index(['nps', 'fecha_encuesta'], 'idx_encuesta_nps_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encuestas_satisfaccion_vehicular');
    }
};
