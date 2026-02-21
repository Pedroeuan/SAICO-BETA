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
          // Kilometraje
            if (!Schema::hasColumn('vehiculos', 'kilometraje_actual')) {
                $table->integer('kilometraje_actual')->default(0)->after('estatus');
            }

            // PDFs
            if (!Schema::hasColumn('vehiculos', 'poliza_seguro_pdf')) {
                $table->string('poliza_seguro_pdf',255)->nullable()->after('kilometraje_actual');
            }

            if (!Schema::hasColumn('vehiculos', 'tarjeta_circulacion_pdf')) {
                $table->string('tarjeta_circulacion_pdf',255)->nullable()->after('poliza_seguro_pdf');
            }

            // Fechas vencimiento
            if (!Schema::hasColumn('vehiculos', 'poliza_seguro_vencimiento')) {
                $table->date('poliza_seguro_vencimiento')->nullable()->after('poliza_seguro_pdf');
            }

            if (!Schema::hasColumn('vehiculos', 'tarjeta_circulacion_vencimiento')) {
                $table->date('tarjeta_circulacion_vencimiento')->nullable()->after('tarjeta_circulacion_pdf');
            }

            // Estatus automático documentación
            if (!Schema::hasColumn('vehiculos', 'documentacion_estatus')) {
                $table->enum('documentacion_estatus', [
                    'completa',
                    'vencida',
                    'incompleta'
                ])
                ->default('incompleta')
                ->after('tarjeta_circulacion_vencimiento');
            }
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
                'tarjeta_circulacion_vencimiento',
                'documentacion_estatus'
            ]);
        });
    }
};
