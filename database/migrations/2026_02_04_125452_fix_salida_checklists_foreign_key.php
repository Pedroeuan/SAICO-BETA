<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('salidas_checklists', function (Blueprint $table) {

            // eliminar foreign key si existe
            $table->dropForeign(['salida_checklists']);
            $table->dropColumn('salida_checklists');

            // crear la correcta
            $table->foreignId('salida_vehiculo_id')
                  ->after('id')
                  ->constrained('salidas_vehiculo')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('salidas_checklists', function (Blueprint $table) {
            $table->dropForeign(['salida_vehiculo_id']);
            $table->dropColumn('salida_vehiculo_id');

            $table->foreignId('salida_checklists')
                  ->constrained('salidas_vehiculo');
        });
    }
};

