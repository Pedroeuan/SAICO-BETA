<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_llantas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('posicion', 40);
            $table->string('marca', 100);
            $table->string('modelo', 100)->nullable();
            $table->string('medida', 50)->nullable();
            $table->string('numero_serie', 120)->nullable();
            $table->date('fecha_instalacion');
            $table->unsignedInteger('kilometraje_instalacion');
            $table->date('fecha_baja')->nullable();
            $table->unsignedInteger('kilometraje_baja')->nullable();
            $table->decimal('costo', 10, 2)->nullable();
            $table->enum('estado', ['activa', 'rotada', 'baja'])->default('activa');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['vehiculo_id', 'estado'], 'idx_llanta_vehiculo_estado');
            $table->index(['vehiculo_id', 'fecha_instalacion'], 'idx_llanta_vehiculo_fecha');
            $table->index(['vehiculo_id', 'posicion'], 'idx_llanta_vehiculo_posicion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_llantas');
    }
};
