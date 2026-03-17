<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('salidas_eventos_flujo')) {
            return;
        }

        $this->assertInnoDb('salidas_eventos_flujo');
        $this->assertInnoDb('salidas_vehiculos');
        $this->assertInnoDb('users');

        $this->assertUnsignedBigInt('salidas_eventos_flujo', 'salida_vehiculo_id');
        $this->assertUnsignedBigInt('salidas_eventos_flujo', 'user_id');
        $this->assertUnsignedBigInt('salidas_vehiculos', 'id');
        $this->assertUnsignedBigInt('users', 'id');

        $orphanSalidas = (int) DB::table('salidas_eventos_flujo as f')
            ->leftJoin('salidas_vehiculos as s', 's.id', '=', 'f.salida_vehiculo_id')
            ->whereNotNull('f.salida_vehiculo_id')
            ->whereNull('s.id')
            ->count();

        if ($orphanSalidas > 0) {
            throw new RuntimeException("No se puede agregar FK fk_flujo_salida: hay {$orphanSalidas} registros huerfanos en salida_vehiculo_id.");
        }

        $orphanUsers = (int) DB::table('salidas_eventos_flujo as f')
            ->leftJoin('users as u', 'u.id', '=', 'f.user_id')
            ->whereNotNull('f.user_id')
            ->whereNull('u.id')
            ->count();

        if ($orphanUsers > 0) {
            throw new RuntimeException("No se puede agregar FK fk_flujo_user: hay {$orphanUsers} registros huerfanos en user_id.");
        }

        if (!$this->foreignExists('salidas_eventos_flujo', 'fk_flujo_salida')) {
            DB::statement("
                ALTER TABLE salidas_eventos_flujo
                ADD CONSTRAINT fk_flujo_salida
                FOREIGN KEY (salida_vehiculo_id)
                REFERENCES salidas_vehiculos(id)
                ON DELETE SET NULL
                ON UPDATE CASCADE
            ");
        }

        if (!$this->foreignExists('salidas_eventos_flujo', 'fk_flujo_user')) {
            DB::statement("
                ALTER TABLE salidas_eventos_flujo
                ADD CONSTRAINT fk_flujo_user
                FOREIGN KEY (user_id)
                REFERENCES users(id)
                ON DELETE SET NULL
                ON UPDATE CASCADE
            ");
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('salidas_eventos_flujo')) {
            return;
        }

        if ($this->foreignExists('salidas_eventos_flujo', 'fk_flujo_salida')) {
            DB::statement('ALTER TABLE salidas_eventos_flujo DROP FOREIGN KEY fk_flujo_salida');
        }

        if ($this->foreignExists('salidas_eventos_flujo', 'fk_flujo_user')) {
            DB::statement('ALTER TABLE salidas_eventos_flujo DROP FOREIGN KEY fk_flujo_user');
        }
    }

    private function foreignExists(string $table, string $constraint): bool
    {
        $schema = DB::getDatabaseName();
        $count = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', $schema)
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->where('CONSTRAINT_NAME', $constraint)
            ->count();

        return $count > 0;
    }

    private function assertInnoDb(string $table): void
    {
        $schema = DB::getDatabaseName();
        $engine = DB::table('information_schema.TABLES')
            ->where('TABLE_SCHEMA', $schema)
            ->where('TABLE_NAME', $table)
            ->value('ENGINE');

        if (strtoupper((string) $engine) !== 'INNODB') {
            throw new RuntimeException("La tabla {$table} debe usar ENGINE=InnoDB para soportar llaves foraneas.");
        }
    }

    private function assertUnsignedBigInt(string $table, string $column): void
    {
        $schema = DB::getDatabaseName();
        $info = DB::table('information_schema.COLUMNS')
            ->select('DATA_TYPE', 'COLUMN_TYPE')
            ->where('TABLE_SCHEMA', $schema)
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->first();

        if (!$info) {
            throw new RuntimeException("No existe la columna {$table}.{$column}.");
        }

        $isBigInt = strtolower((string) $info->DATA_TYPE) === 'bigint';
        $isUnsigned = str_contains(strtolower((string) $info->COLUMN_TYPE), 'unsigned');

        if (!$isBigInt || !$isUnsigned) {
            throw new RuntimeException("La columna {$table}.{$column} debe ser BIGINT UNSIGNED.");
        }
    }
};
