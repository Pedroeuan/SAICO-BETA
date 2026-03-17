<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('checklist_condiciones', function (Blueprint $table) {
            $table->foreignId('salida_checklist_id')
                  ->after('id')
                  ->constrained('salidas_checklists')
                  ->cascadeOnDelete();

            $table->string('nivel_gasolina');
            $table->integer('kilometraje');
            $table->boolean('limpio_exterior');
            $table->boolean('limpio_interior');
            $table->text('observaciones')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('checklist_condiciones', function (Blueprint $table) {
            $table->dropForeign(['salida_checklist_id']);
            $table->dropColumn([
                'salida_checklist_id',
                'nivel_gasolina',
                'kilometraje',
                'limpio_exterior',
                'limpio_interior',
                'observaciones',
            ]);
        });
    }
};
