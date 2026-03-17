<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salidas_eventos_flujo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salida_vehiculo_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('rol', 100)->nullable();
            $table->string('evento', 80);
            $table->string('paso', 80)->nullable();
            $table->string('pantalla', 120)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['evento', 'created_at'], 'idx_flujo_evento_fecha');
            $table->index(['rol', 'created_at'], 'idx_flujo_rol_fecha');
            $table->index(['user_id', 'created_at'], 'idx_flujo_user_fecha');
            $table->index(['salida_vehiculo_id', 'evento'], 'idx_flujo_salida_evento');

            // Nota: esta tabla es analitica; se deja sin FK estrictas para
            // evitar incompatibilidades con esquemas existentes en produccion.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salidas_eventos_flujo');
    }
};
