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
        Schema::create('salidas_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salida_vehiculo_id')->constrained()->cascadeOnDelete();
            $table->enum('tipo',['salida','entrada']);
            $table->timestamps();
            $table->unique(['salida_vehiculo_id','tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salidas_checklists');
    }
};
