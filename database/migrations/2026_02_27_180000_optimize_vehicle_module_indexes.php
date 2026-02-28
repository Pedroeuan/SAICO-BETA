<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function hasIndex(string $table, string $index): bool
    {
        $rows = DB::select("SHOW INDEX FROM `$table` WHERE Key_name = ?", [$index]);
        return !empty($rows);
    }

    public function up(): void
    {
        // vehiculos: usado en filtros del panel/listados.
        if (Schema::hasTable('vehiculos') && !$this->hasIndex('vehiculos', 'idx_vehiculos_documentacion_estatus')) {
            Schema::table('vehiculos', function (Blueprint $table) {
                $table->index('documentacion_estatus', 'idx_vehiculos_documentacion_estatus');
            });
        }

        // mantenimientos: consultas por vehiculo + rango de fecha.
        if (Schema::hasTable('mantenimientos') && !$this->hasIndex('mantenimientos', 'idx_mant_vehiculo_fecha')) {
            Schema::table('mantenimientos', function (Blueprint $table) {
                $table->index(['vehiculo_id', 'fecha'], 'idx_mant_vehiculo_fecha');
            });
        }

        // pagos_vehiculo: consultas por vehiculo + rango de fecha.
        if (Schema::hasTable('pagos_vehiculo') && !$this->hasIndex('pagos_vehiculo', 'idx_pago_vehiculo_fecha')) {
            Schema::table('pagos_vehiculo', function (Blueprint $table) {
                $table->index(['vehiculo_id', 'fecha_pago'], 'idx_pago_vehiculo_fecha');
            });
        }

        // salidas_vehiculos: reportes filtran por fecha y estatus.
        if (Schema::hasTable('salidas_vehiculos') && !$this->hasIndex('salidas_vehiculos', 'idx_salidas_fecha_estatus')) {
            Schema::table('salidas_vehiculos', function (Blueprint $table) {
                $table->index(['fecha_salida', 'estatus'], 'idx_salidas_fecha_estatus');
            });
        }

        // Limpieza de índices duplicados detectados en checklist_condiciones.
        if (
            Schema::hasTable('checklist_condiciones') &&
            $this->hasIndex('checklist_condiciones', 'fk_checklist_condiciones_salidas_checklists1_idx') &&
            $this->hasIndex('checklist_condiciones', 'idx_condiciones_checklist')
        ) {
            Schema::table('checklist_condiciones', function (Blueprint $table) {
                $table->dropIndex('idx_condiciones_checklist');
            });
        }

        // Limpieza de índices duplicados detectados en checklist_evidencias.
        if (
            Schema::hasTable('checklist_evidencias') &&
            $this->hasIndex('checklist_evidencias', 'fk_checklist_evidencias_salidas_checklists1_idx') &&
            $this->hasIndex('checklist_evidencias', 'idx_evidencias_checklist')
        ) {
            Schema::table('checklist_evidencias', function (Blueprint $table) {
                $table->dropIndex('idx_evidencias_checklist');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('vehiculos') && $this->hasIndex('vehiculos', 'idx_vehiculos_documentacion_estatus')) {
            Schema::table('vehiculos', function (Blueprint $table) {
                $table->dropIndex('idx_vehiculos_documentacion_estatus');
            });
        }

        if (Schema::hasTable('mantenimientos') && $this->hasIndex('mantenimientos', 'idx_mant_vehiculo_fecha')) {
            Schema::table('mantenimientos', function (Blueprint $table) {
                $table->dropIndex('idx_mant_vehiculo_fecha');
            });
        }

        if (Schema::hasTable('pagos_vehiculo') && $this->hasIndex('pagos_vehiculo', 'idx_pago_vehiculo_fecha')) {
            Schema::table('pagos_vehiculo', function (Blueprint $table) {
                $table->dropIndex('idx_pago_vehiculo_fecha');
            });
        }

        if (Schema::hasTable('salidas_vehiculos') && $this->hasIndex('salidas_vehiculos', 'idx_salidas_fecha_estatus')) {
            Schema::table('salidas_vehiculos', function (Blueprint $table) {
                $table->dropIndex('idx_salidas_fecha_estatus');
            });
        }

        // Reponer índices retirados en limpieza (si no existen).
        if (Schema::hasTable('checklist_condiciones') && !$this->hasIndex('checklist_condiciones', 'idx_condiciones_checklist')) {
            Schema::table('checklist_condiciones', function (Blueprint $table) {
                $table->index('salida_checklist_id', 'idx_condiciones_checklist');
            });
        }

        if (Schema::hasTable('checklist_evidencias') && !$this->hasIndex('checklist_evidencias', 'idx_evidencias_checklist')) {
            Schema::table('checklist_evidencias', function (Blueprint $table) {
                $table->index('salida_checklist_id', 'idx_evidencias_checklist');
            });
        }
    }
};

