<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salidas_vehiculo', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('vehiculo_id');
            // IDs de users (SIN foreign key)
            $table->bigInteger('chofer_id'); // probelmas con foreign keys
            $table->bigInteger('solicitado_por');

            $table->dateTime('fecha_salida');
            $table->dateTime('fecha_regreso')->nullable();
            $table->text('motivo')->nullable();
            $table->string('estatus')->default('activo');

            $table->timestamps();
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salidas_vehiculo');
    }
};
