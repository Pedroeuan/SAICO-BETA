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
            $table->foreign('chofer_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('solicitado_por')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salidas_vehiculos', function (Blueprint $table) {
            $table->dropForeign(['chofer_id']);
            $table->dropForeign(['solicitado_por']);
        });
    }
};
