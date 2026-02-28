<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pagos_vehiculo') || !Schema::hasTable('vehiculos')) {
            return;
        }

        // Quitar FK previa si existe
        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'pagos_vehiculo'
              AND COLUMN_NAME = 'vehiculo_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");

        if ($fk) {
            DB::statement("ALTER TABLE pagos_vehiculo DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // Asegurar mismo tipo que vehiculos.id (INT signed)
        DB::statement("ALTER TABLE pagos_vehiculo MODIFY COLUMN vehiculo_id INT(11) NOT NULL");

        // Índice para FK
        $idx = DB::selectOne("
            SELECT INDEX_NAME
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'pagos_vehiculo'
              AND INDEX_NAME = 'idx_pago_vehiculo'
            LIMIT 1
        ");
        if (!$idx) {
            DB::statement("ALTER TABLE pagos_vehiculo ADD INDEX idx_pago_vehiculo (vehiculo_id)");
        }

        // Crear FK
        DB::statement("
            ALTER TABLE pagos_vehiculo
            ADD CONSTRAINT fk_pagos_vehiculo_vehiculo
            FOREIGN KEY (vehiculo_id)
            REFERENCES vehiculos(id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
        ");
    }

    public function down(): void
    {
        if (!Schema::hasTable('pagos_vehiculo')) {
            return;
        }

        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'pagos_vehiculo'
              AND COLUMN_NAME = 'vehiculo_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");

        if ($fk) {
            DB::statement("ALTER TABLE pagos_vehiculo DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }
    }
};
