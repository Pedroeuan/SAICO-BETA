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
            //cambiar de nombre update x updated
            if(Schema::hasTable('salidas_checklists')&& Schema::hasColumn('salidas_checklists','update_at')&& !Schema::hasColumn('salidas_checklists','updated_at')){
                Schema::table('salidas_checklists', function (Blueprint $table){
                    $table->renameColumn('update_at','updated_at');
                });
            }
            if (Schema::hasTable('checklist_evidencias') && Schema::hasColumn('checklist_evidencias', 'update_at') && !Schema::hasColumn('checklist_evidencias', 'updated_at')) {
            Schema::table('checklist_evidencias', function (Blueprint $table) {
                $table->renameColumn('update_at', 'updated_at');
            });
        }
        if (Schema::hasTable('checklist_herramientas') && Schema::hasColumn('checklist_herramientas', 'update_at') && !Schema::hasColumn('checklist_herramientas', 'updated_at')) {
            Schema::table('checklist_herramientas', function (Blueprint $table) {
                $table->renameColumn('update_at', 'updated_at');
            });
        }
        if (Schema::hasTable('checklist_documentos') && Schema::hasColumn('checklist_documentos', 'update_at') && !Schema::hasColumn('checklist_documentos', 'updated_at')) {
            Schema::table('checklist_documentos', function (Blueprint $table) {
                $table->renameColumn('update_at', 'updated_at');
            });

        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversa segura para rollback.
        if (Schema::hasTable('salidas_checklists') && Schema::hasColumn('salidas_checklists', 'updated_at') && !Schema::hasColumn('salidas_checklists', 'update_at')) {
            Schema::table('salidas_checklists', function (Blueprint $table) {
                $table->renameColumn('updated_at', 'update_at');
            });
        }
        
        if (Schema::hasTable('checklist_evidencias') && Schema::hasColumn('checklist_evidencias', 'updated_at') && !Schema::hasColumn('checklist_evidencias', 'update_at')) {
            Schema::table('checklist_evidencias', function (Blueprint $table) {
                $table->renameColumn('updated_at', 'update_at');
            });
        }

        if (Schema::hasTable('checklist_herramientas') && Schema::hasColumn('checklist_herramientas', 'updated_at') && !Schema::hasColumn('checklist_herramientas', 'update_at')) {
            Schema::table('checklist_herramientas', function (Blueprint $table) {
                $table->renameColumn('updated_at', 'update_at');
            });
        }

         if (Schema::hasTable('checklist_documentos')
            && Schema::hasColumn('checklist_documentos', 'updated_at') && !Schema::hasColumn('checklist_documentos', 'update_at')) {
            Schema::table('checklist_documentos', function (Blueprint $table) {
                $table->renameColumn('updated_at', 'update_at');
            });
        }

    }
};
