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
            if (!Schema::hasColumn('vehiculos', 'foto_principal')) {
                $table->string('foto_principal', 255)->nullable()->after('modelo');
            }

            if (!Schema::hasColumn('vehiculos', 'tenencia_vencimiento')) {
                $table->date('tenencia_vencimiento')->nullable()->after('tarjeta_circulacion_vencimiento');
            }

            if (!Schema::hasColumn('vehiculos', 'tenencia_estatus')) {
                $table->enum('tenencia_estatus', ['vigente', 'proxima', 'vencida', 'sin_registro'])
                    ->default('sin_registro')
                    ->after('tenencia_vencimiento');
            }
        });

        Schema::table('vehiculos', function (Blueprint $table) {
            $table->index('tenencia_vencimiento', 'idx_vehiculos_tenencia_vencimiento');
            $table->index('tenencia_estatus', 'idx_vehiculos_tenencia_estatus');
            $table->index('estatus', 'idx_vehiculos_estatus');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) { 
            $table->dropIndex('idx_vehiculos_tenencia_vencimiento');
            $table->dropIndex('idx_vehiculos_tenencia_estatus');
            $table->dropIndex('idx_vehiculos_estatus');

            if (Schema::hasColumn('vehiculos', 'tenencia_estatus')) {
                $table->dropColumn('tenencia_estatus');
            }
            if (Schema::hasColumn('vehiculos', 'tenencia_vencimiento')) {
                $table->dropColumn('tenencia_vencimiento');
            }
            if (Schema::hasColumn('vehiculos', 'foto_principal')) {
                $table->dropColumn('foto_principal');
            }
        });
    }
};
