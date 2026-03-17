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
        Schema::table('salidas_vehiculos', function (Blueprint $table) {
            $table->bigInteger('creado_por')->nullable()->after('solicitado_por');
            $table->bigInteger('finalizado_por')->nullable()->after('creado_por');

            $table->foreign('creado_por')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('finalizado_por')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salidas_vehiculos', function (Blueprint $table) {
            $table->dropForeign(['creado_por']);
            $table->dropForeign(['finalizado_por']);
            $table->dropColumn(['creado_por','finalizado_por']);
        });
    }
};
