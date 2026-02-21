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

            $table->foreignId('vehiculo_id')->constrained()->restrictOnDelete();
            // IDs de users (SIN foreign key)
            $table-foreignId('chofer_id')->constrained('users'); // probelmas con foreign keys
            $table->foreignId('solicitado_por')->constrained('users');

            $table->dateTime('fecha_salida');
            $table->dateTime('fecha_regreso')->nullable();
            $table->text('motivo')->nullable();
            $table->enum('estatus',['activo','finaliizado'])->default('activo');
            $table->timestamps();
            $table->unique(['vehiculo_id','estatus']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salidas_vehiculo');
    }
};
