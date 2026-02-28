<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->integer('vehiculo_id');
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->onDelete('cascade')->onUpdate('cascade');

            $table->enum('tipo', ['preventivo', 'correctivo']);
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->integer('kilometraje')->nullable();
            $table->decimal('costo', 10, 2)->nullable();

            $table->date('proxima_revision_fecha')->nullable();
            $table->integer('proxima_revision_km')->nullable();

            $table->string('factura_pdf', 255)->nullable();
            $table->string('factura_numero', 100)->nullable();
            $table->date('factura_fecha')->nullable();
            $table->decimal('factura_monto', 10, 2)->nullable();

            $table->timestamps();

            $table->index('vehiculo_id', 'idx_mant_vehiculo');
            $table->index('fecha', 'idx_mant_fecha');
            $table->index('proxima_revision_fecha', 'idx_mant_prox_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenimientos');
    }
};
