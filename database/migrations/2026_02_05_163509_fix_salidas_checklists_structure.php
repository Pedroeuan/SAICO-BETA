<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('salidas_checklists', function (Blueprint $table) {
            $table->dropColumn([
                'nivel_gasolina',
                'kilometraje',
                'limpio_exterior',
                'limpio_interior',
                'observaciones',
            ]);
        });
    }

    public function down()
    {
        Schema::table('salidas_checklists', function (Blueprint $table) {
            $table->string('nivel_gasolina');
            $table->integer('kilometraje');
            $table->boolean('limpio_exterior');
            $table->boolean('limpio_interior');
            $table->text('observaciones')->nullable();
        });
    }
};
