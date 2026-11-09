<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('OC', 'Revision_OC')) {
            Schema::table('OC', function (Blueprint $table) {
                $table->longText('Revision_OC')->nullable()->after('Revision');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('OC', 'Revision_OC')) {
            Schema::table('OC', function (Blueprint $table) {
                $table->dropColumn('Revision_OC');
            });
        }
    }
};
