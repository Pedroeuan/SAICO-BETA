<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('checklist_condiciones')) {
            return;
        }

        Schema::table('checklist_condiciones', function (Blueprint $table) {
            if (!Schema::hasColumn('checklist_condiciones', 'liquido_frenos')) {
                $table->string('liquido_frenos', 20)->nullable()->after('anticongelante');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('checklist_condiciones')) {
            return;
        }

        Schema::table('checklist_condiciones', function (Blueprint $table) {
            if (Schema::hasColumn('checklist_condiciones', 'liquido_frenos')) {
                $table->dropColumn('liquido_frenos');
            }
        });
    }
};
