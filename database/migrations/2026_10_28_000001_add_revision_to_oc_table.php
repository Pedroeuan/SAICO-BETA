<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('OC', 'Revision')) {
            Schema::table('OC', function (Blueprint $table) {
                $table->string('Revision', 50)->nullable()->after('Estatus');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('OC', 'Revision')) {
            Schema::table('OC', function (Blueprint $table) {
                $table->dropColumn('Revision');
            });
        }
    }
};
