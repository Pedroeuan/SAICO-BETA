<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Comentario que agrega el usuario de ventas después de una revisión.
     * Se guarda aparte de Revision_OC para no sobrescribir las observaciones
     * originales del revisor.
     */
    public function up(): void
    {
        Schema::table('OC', function (Blueprint $table) {
            if (!Schema::hasColumn('OC', 'Comentario_Ventas')) {
                $table->longText('Comentario_Ventas')->nullable()->after('Revision_OC');
            }
            if (!Schema::hasColumn('OC', 'Comentario_Ventas_Autor')) {
                $table->string('Comentario_Ventas_Autor', 255)->nullable()->after('Comentario_Ventas');
            }
            if (!Schema::hasColumn('OC', 'Comentario_Ventas_At')) {
                $table->dateTime('Comentario_Ventas_At')->nullable()->after('Comentario_Ventas_Autor');
            }
        });
    }

    public function down(): void
    {
        Schema::table('OC', function (Blueprint $table) {
            if (Schema::hasColumn('OC', 'Comentario_Ventas_At')) {
                $table->dropColumn('Comentario_Ventas_At');
            }
            if (Schema::hasColumn('OC', 'Comentario_Ventas_Autor')) {
                $table->dropColumn('Comentario_Ventas_Autor');
            }
            if (Schema::hasColumn('OC', 'Comentario_Ventas')) {
                $table->dropColumn('Comentario_Ventas');
            }
        });
    }
};
