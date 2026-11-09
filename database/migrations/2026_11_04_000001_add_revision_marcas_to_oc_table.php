<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('OC', 'Revision_Marcas')) {
            Schema::table('OC', function (Blueprint $table) {
                $table->longText('Revision_Marcas')->nullable()->after('Revision_OC');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('OC', 'Revision_Marcas')) {
            Schema::table('OC', function (Blueprint $table) {
                $table->dropColumn('Revision_Marcas');
            });
        }
    }
};
