<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('Normas_IM', function (Blueprint $table) {
            $table->longText('Tablas_Adicionales')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('Normas_IM', fn (Blueprint $table) => $table->dropColumn('Tablas_Adicionales'));
    }
};
