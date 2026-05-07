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
        return count($rows) > 0;
    }

    public function up(): void
    {
        if (Schema::hasTable('vehiculos')) {
            Schema::table('vehiculos', function (Blueprint $table) {
                // ya tienes algunos índices en esta tabla; aquí sólo agregamos estatus
            });
            if (!$this->hasIndex('vehiculos', 'idx_vehiculos_estatus')) {
                Schema::table('vehiculos', function (Blueprint $table) {
                    $table->index('estatus', 'idx_vehiculos_estatus');
                });
            }
        }

        if (Schema::hasTable('salidas_vehiculos')) {
            if (!$this->hasIndex('salidas_vehiculos', 'idx_salidas_fecha_salida')) {
                Schema::table('salidas_vehiculos', fn (Blueprint $t) => $t->index('fecha_salida', 'idx_salidas_fecha_salida'));
            }
            if (!$this->hasIndex('salidas_vehiculos', 'idx_salidas_estatus')) {
                Schema::table('salidas_vehiculos', fn (Blueprint $t) => $t->index('estatus', 'idx_salidas_estatus'));
            }
            if (!$this->hasIndex('salidas_vehiculos', 'idx_salidas_vehiculo_estatus')) {
                Schema::table('salidas_vehiculos', fn (Blueprint $t) => $t->index(['vehiculo_id', 'estatus'], 'idx_salidas_vehiculo_estatus'));
            }
            if (!$this->hasIndex('salidas_vehiculos', 'idx_salidas_chofer_estatus')) {
                Schema::table('salidas_vehiculos', fn (Blueprint $t) => $t->index(['chofer_id', 'estatus'], 'idx_salidas_chofer_estatus'));
            }
        }

        if (Schema::hasTable('salidas_checklists') && !$this->hasIndex('salidas_checklists', 'idx_checklists_salida_tipo')) {
            Schema::table('salidas_checklists', fn (Blueprint $t) => $t->index(['salida_vehiculo_id', 'tipo'], 'idx_checklists_salida_tipo'));
        }

        if (Schema::hasTable('checklist_condiciones') && !$this->hasIndex('checklist_condiciones', 'idx_condiciones_checklist')) {
            Schema::table('checklist_condiciones', fn (Blueprint $t) => $t->index('salida_checklist_id', 'idx_condiciones_checklist'));
        }

        if (Schema::hasTable('checklist_evidencias') && !$this->hasIndex('checklist_evidencias', 'idx_evidencias_checklist')) {
            Schema::table('checklist_evidencias', fn (Blueprint $t) => $t->index('salida_checklist_id', 'idx_evidencias_checklist'));
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('vehiculos') && $this->hasIndex('vehiculos', 'idx_vehiculos_estatus')) {
            Schema::table('vehiculos', fn (Blueprint $t) => $t->dropIndex('idx_vehiculos_estatus'));
        }

        if (Schema::hasTable('salidas_vehiculos')) {
            if ($this->hasIndex('salidas_vehiculos', 'idx_salidas_fecha_salida')) {
                Schema::table('salidas_vehiculos', fn (Blueprint $t) => $t->dropIndex('idx_salidas_fecha_salida'));
            }
            if ($this->hasIndex('salidas_vehiculos', 'idx_salidas_estatus')) {
                Schema::table('salidas_vehiculos', fn (Blueprint $t) => $t->dropIndex('idx_salidas_estatus'));
            }
            if ($this->hasIndex('salidas_vehiculos', 'idx_salidas_vehiculo_estatus')) {
                Schema::table('salidas_vehiculos', fn (Blueprint $t) => $t->dropIndex('idx_salidas_vehiculo_estatus'));
            }
            if ($this->hasIndex('salidas_vehiculos', 'idx_salidas_chofer_estatus')) {
                Schema::table('salidas_vehiculos', fn (Blueprint $t) => $t->dropIndex('idx_salidas_chofer_estatus'));
            }
        }

        if (Schema::hasTable('salidas_checklists') && $this->hasIndex('salidas_checklists', 'idx_checklists_salida_tipo')) {
            Schema::table('salidas_checklists', fn (Blueprint $t) => $t->dropIndex('idx_checklists_salida_tipo'));
        }

        if (Schema::hasTable('checklist_condiciones') && $this->hasIndex('checklist_condiciones', 'idx_condiciones_checklist')) {
            Schema::table('checklist_condiciones', fn (Blueprint $t) => $t->dropIndex('idx_condiciones_checklist'));
        }

        if (Schema::hasTable('checklist_evidencias') && $this->hasIndex('checklist_evidencias', 'idx_evidencias_checklist')) {
            Schema::table('checklist_evidencias', fn (Blueprint $t) => $t->dropIndex('idx_evidencias_checklist'));
        }
    }
};
