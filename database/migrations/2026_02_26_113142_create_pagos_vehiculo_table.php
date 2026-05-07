<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pagos_vehiculo')) {
            Schema::create('pagos_vehiculo', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete()->cascadeOnUpdate();
                $table->enum('tipo_pago', ['tenencia', 'refrendo', 'verificacion']);
                $table->year('anio');
                $table->decimal('monto', 10, 2)->nullable();
                $table->date('fecha_pago')->nullable();
                $table->string('comprobante_url', 255)->nullable();
                $table->timestamps();

                $table->index('vehiculo_id', 'idx_pago_vehiculo');
                $table->index('anio', 'idx_pago_anio');
                $table->index('tipo_pago', 'idx_pago_tipo');
                $table->unique(['vehiculo_id', 'tipo_pago', 'anio'], 'uq_pago_vehiculo_tipo_anio');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_vehiculo');
    }
};
