<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Los comentarios ya viven en OC_Comentarios. Estas columnas solo
     * conservaban el último comentario y dejaron de ser necesarias.
     */
    public function up(): void
    {
        $columnas = [
            'Revision_OC',
            'Comentario_Ventas',
            'Comentario_Ventas_Autor',
            'Comentario_Ventas_At',
        ];

        foreach ($columnas as $columna) {
            if (Schema::hasColumn('OC', $columna)) {
                Schema::table('OC', function (Blueprint $table) use ($columna) {
                    $table->dropColumn($columna);
                });
            }
        }
    }

    /**
     * Restaura únicamente la estructura. Los datos no se recuperan.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('OC', 'Revision_OC')) {
            Schema::table('OC', function (Blueprint $table) {
                $table->longText('Revision_OC')->nullable();
            });
        }

        if (!Schema::hasColumn('OC', 'Comentario_Ventas')) {
            Schema::table('OC', function (Blueprint $table) {
                $table->longText('Comentario_Ventas')->nullable();
            });
        }

        if (!Schema::hasColumn('OC', 'Comentario_Ventas_Autor')) {
            Schema::table('OC', function (Blueprint $table) {
                $table->string('Comentario_Ventas_Autor', 255)->nullable();
            });
        }

        if (!Schema::hasColumn('OC', 'Comentario_Ventas_At')) {
            Schema::table('OC', function (Blueprint $table) {
                $table->dateTime('Comentario_Ventas_At')->nullable();
            });
        }
    }
};
