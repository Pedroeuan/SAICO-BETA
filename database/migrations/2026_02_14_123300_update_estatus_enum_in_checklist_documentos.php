<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE checklist_documentos 
            MODIFY estatus ENUM('ok','vencido','faltante') 
            NOT NULL DEFAULT 'faltante'
        ");
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE checklist_documentos 
            MODIFY estatus ENUM('ok','vencido') 
            NOT NULL DEFAULT 'ok'
        ");
    }
};
