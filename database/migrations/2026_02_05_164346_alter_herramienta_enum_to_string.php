<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('checklist_herramientas', function (Blueprint $table) {
            $table->string('herramienta', 100)->change();
        });
    }

    public function down(): void
    {
        Schema::table('checklist_herramientas', function (Blueprint $table) {
            $table->enum('herramienta',[
                'llantas',
                'extintor',
                'cable_corriente',
                'gato_hidraulico',
                'llave_cruz',
                'llanta_refaccion'
            ])->change();
        });
    }
};

