<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('checklist_condiciones')) {
            return;
        }

        Schema::table('checklist_condiciones', function (Blueprint $table) {
            if (!Schema::hasColumn('checklist_condiciones', 'liquido_limpiaparabrisas')) {
                $table->string('liquido_limpiaparabrisas', 20)->nullable()->after('observaciones');
            }
            if (!Schema::hasColumn('checklist_condiciones', 'aceite')) {
                $table->string('aceite', 20)->nullable()->after('liquido_limpiaparabrisas');
            }
            if (!Schema::hasColumn('checklist_condiciones', 'anticongelante')) {
                $table->string('anticongelante', 20)->nullable()->after('aceite');
            }
            if (!Schema::hasColumn('checklist_condiciones', 'estado_llantas')) {
                $table->string('estado_llantas', 20)->nullable()->after('anticongelante');
            }
            if (!Schema::hasColumn('checklist_condiciones', 'llanta_delantera_izq_calibracion')) {
                $table->string('llanta_delantera_izq_calibracion', 20)->nullable()->after('estado_llantas');
            }
            if (!Schema::hasColumn('checklist_condiciones', 'llanta_delantera_der_calibracion')) {
                $table->string('llanta_delantera_der_calibracion', 20)->nullable()->after('llanta_delantera_izq_calibracion');
            }
            if (!Schema::hasColumn('checklist_condiciones', 'llanta_trasera_izq_calibracion')) {
                $table->string('llanta_trasera_izq_calibracion', 20)->nullable()->after('llanta_delantera_der_calibracion');
            }
            if (!Schema::hasColumn('checklist_condiciones', 'llanta_trasera_der_calibracion')) {
                $table->string('llanta_trasera_der_calibracion', 20)->nullable()->after('llanta_trasera_izq_calibracion');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('checklist_condiciones')) {
            return;
        }

        Schema::table('checklist_condiciones', function (Blueprint $table) {
            $columns = [
                'liquido_limpiaparabrisas',
                'aceite',
                'anticongelante',
                'estado_llantas',
                'llanta_delantera_izq_calibracion',
                'llanta_delantera_der_calibracion',
                'llanta_trasera_izq_calibracion',
                'llanta_trasera_der_calibracion',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('checklist_condiciones', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

