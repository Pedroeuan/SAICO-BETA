<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('checklist_condiciones');
        Schema::dropIfExists('checklist_documentos');
        Schema::dropIfExists('checklist_herramientas');
        Schema::dropIfExists('salidas_checklists');
        Schema::dropIfExists('salidas_vehiculos');
        Schema::dropIfExists('vehiculos');

        Schema::enableForeignKeyConstraints();

        // ====== TABLAS ======

        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa')->unique();
            $table->string('marca');
            $table->string('modelo');
            $table->year('anio')->nullable();
            $table->enum('estatus',['disponible','ocupado','inactivo'])->default('disponible');
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';

        });

    Schema::create('salidas_vehiculos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vehiculo_id')->constrained()->restrictOnDelete();

    // relación con usuario
    $table->unsignedBigInteger('chofer_id');
    $table->unsignedBigInteger('solicitado_por');

    $table->dateTime('fecha_salida');
    $table->dateTime('fecha_regreso')->nullable();
    $table->text('motivo')->nullable();
    $table->enum('estatus',['activo','finalizado'])->default('activo');
    $table->timestamps();

    $table->unique(['vehiculo_id','estatus']);

    $table->engine = 'InnoDB';
    $table->charset = 'utf8mb4';
});



        Schema::create('salidas_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salida_vehiculo_id')
                  ->constrained('salidas_vehiculos')
                  ->cascadeOnDelete();
            $table->enum('tipo',['salida','entrada']);
            $table->timestamps();

            $table->unique(['salida_vehiculo_id','tipo']);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        Schema::create('checklist_condiciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salida_checklist_id')
                  ->constrained('salidas_checklists')
                  ->cascadeOnDelete();
            $table->string('nivel_gasolina');
            $table->integer('kilometraje');
            $table->boolean('limpio_exterior');
            $table->boolean('limpio_interior');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        Schema::create('checklist_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salida_checklist_id')
                  ->constrained('salidas_checklists')
                  ->cascadeOnDelete();
            $table->string('documento');
            $table->enum('estatus',['ok','vencido']);
            $table->timestamps();


            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';

        });

        Schema::create('checklist_herramientas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salida_checklist_id')
                  ->constrained('salidas_checklists')
                  ->cascadeOnDelete();
            $table->string('herramienta');
            $table->boolean('disponible');
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';

        });
    }

    public function down(): void
    {
        // intentionally left empty
    }
};
