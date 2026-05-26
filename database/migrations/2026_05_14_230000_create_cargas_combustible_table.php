<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargas_combustible', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('fecha_carga');
            $table->unsignedInteger('kilometraje');
            $table->decimal('litros', 10, 3);
            $table->decimal('costo_total', 10, 2);
            $table->decimal('precio_por_litro', 10, 4)->nullable();
            $table->enum('tipo_combustible', ['magna', 'premium', 'diesel', 'otro'])->default('magna');
            $table->string('proveedor', 150)->nullable();
            $table->boolean('tanque_lleno')->default(false);
            $table->string('ticket_url', 255)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['vehiculo_id', 'fecha_carga'], 'idx_carga_combustible_vehiculo_fecha');
            $table->index(['vehiculo_id', 'kilometraje'], 'idx_carga_combustible_vehiculo_km');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargas_combustible');
    }
};
