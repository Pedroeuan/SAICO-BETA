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
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->index('poliza_seguro_vencimiento');
            $table->index('tarjeta_circulacion_vencimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropIndex(['poliza_seguro_vencimiento']);
            $table->dropIndex(['tarjeta_circulacion_vencimiento']);
        });
    }
};
