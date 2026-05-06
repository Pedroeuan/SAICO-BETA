<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publicaciones', function (Blueprint $table): void {
            $table->timestamp('programado_at')->nullable()->after('publicado_at');
            $table->index(['programado_at', 'publicado_en_redes'], 'publicaciones_programado_publicado_idx');
        });
    }

    public function down(): void
    {
        Schema::table('publicaciones', function (Blueprint $table): void {
            $table->dropIndex('publicaciones_programado_publicado_idx');
            $table->dropColumn('programado_at');
        });
    }
};
