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
        Schema::table('checklist_evidencias', function (Blueprint $table) {
        $table->foreignId('salida_checklist_id')
              ->after('id')
              ->constrained('salidas_checklists')
              ->onDelete('cascade');

        $table->string('foto')->after('salida_checklist_id');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checklist_evidencias', function (Blueprint $table) {
        $table->dropForeign(['salida_checklist_id']);
        $table->dropColumn(['salida_checklist_id', 'foto']);
    });
    }
};
