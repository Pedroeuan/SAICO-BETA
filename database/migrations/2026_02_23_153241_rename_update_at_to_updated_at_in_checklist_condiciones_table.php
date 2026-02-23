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
        if (Schema::hasTable('checklist_condiciones') && Schema::hasColumn('checklist_condiciones', 'update_at') && !Schema::hasColumn('checklist_condiciones', 'updated_at')) {
            Schema::table('checklist_condiciones', function (Blueprint $table) {
                $table->renameColumn('update_at', 'updated_at');
    });
}

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('checklist_condiciones') && Schema::hasColumn('checklist_condiciones', 'updated_at') && !Schema::hasColumn('checklist_condiciones', 'update_at')) {
            Schema::table('checklist_condiciones', function (Blueprint $table) {
                $table->renameColumn('updated_at', 'update_at');
    });
}

    }
};
