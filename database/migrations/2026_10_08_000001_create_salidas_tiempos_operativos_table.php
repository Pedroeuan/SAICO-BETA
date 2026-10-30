<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salidas_tiempos_operativos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salida_vehiculo_id');
            $table->string('tipo', 10);
            $table->dateTime('ocurrido_at');
            $table->dateTime('registrado_at');
            $table->unsignedBigInteger('registrado_por')->nullable();
            $table->string('canal', 10);
            $table->string('entorno', 50);
            $table->string('zona_horaria', 80);
            $table->json('contexto');
            $table->unique(['salida_vehiculo_id', 'tipo'], 'salida_tiempo_tipo_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salidas_tiempos_operativos');
    }
};
