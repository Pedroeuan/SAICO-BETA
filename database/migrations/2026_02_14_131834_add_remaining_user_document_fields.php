<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Agregamos solo lo que falta

            if (!Schema::hasColumn('users', 'licencia_vencimiento')) {
                $table->date('licencia_vencimiento')->nullable();
            }

            if (!Schema::hasColumn('users', 'licencia_pdf')) {
                $table->string('licencia_pdf')->nullable();
            }

            if (!Schema::hasColumn('users', 'licencia_estatus')) {
                $table->enum('licencia_estatus', [
                    'vigente',
                    'vencida',
                    'no_aplica'
                ])->default('no_aplica');
            }

            if (!Schema::hasColumn('users', 'cv_pdf')) {
                $table->string('cv_pdf')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'licencia_vencimiento',
                'licencia_pdf',
                'licencia_estatus',
                'cv_pdf'
            ]);
        });
    }
};
