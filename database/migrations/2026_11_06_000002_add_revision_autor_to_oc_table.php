<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Nombre del usuario que finaliza la revisión de la OC y fecha en que
     * se guardó esa revisión.
     */
    public function up(): void
    {
        Schema::table('OC', function (Blueprint $table) {
            if (!Schema::hasColumn('OC', 'Revision_Autor')) {
                $table->string('Revision_Autor', 255)->nullable()->after('Revision_Marcas');
            }
            if (!Schema::hasColumn('OC', 'Revision_At')) {
                $table->dateTime('Revision_At')->nullable()->after('Revision_Autor');
            }
        });
    }

    public function down(): void
    {
        Schema::table('OC', function (Blueprint $table) {
            if (Schema::hasColumn('OC', 'Revision_At')) {
                $table->dropColumn('Revision_At');
            }
            if (Schema::hasColumn('OC', 'Revision_Autor')) {
                $table->dropColumn('Revision_Autor');
            }
        });
    }
};
