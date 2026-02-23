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
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->integer('kilometraje_actual')->default(0)->after('estatus');

            $table->string('poliza_seguro_pdf',255)->nullable()->after('kilometraje_actual');

            $table->string('tarjeta_circulacion_pdf',255)->nullable()->after('poliza_seguro_pdf');

            $table->date('poliza_seguro_vencimiento')->nullable()->after('poliza_seguro_pdf');

            $table->date('tarjeta_circulacion_vencimiento')->nullable()->after('tarjeta_circulacion_pdf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropColumn([
                'kilometraje_actual',
                'poliza_seguro_pdf',
                'tarjeta_circulacion_pdf',
                'poliza_seguro_vencimiento',
                'tarjeta_circulacion_vencimiento'
            ]);     
        });
    }
};
