<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('checklist_herramientas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salida_checklist_id')->constrained('salidas_checklists')->cascadeOnDelete();
            $table->enum('herramienta',['llantas','extintor','cable_corriente','gato_hidraulico','llave_cruz','llanta_refaccion']);
            $table->boolean('disponible')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_herramientas');
    }
};
