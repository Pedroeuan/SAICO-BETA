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
        Schema::create('comentarios_reporte', function (Blueprint $table) {
            $table->id('idComentario');
            $table->bigInteger('idReportes')->unsigned();
            $table->text('comentario');
            $table->string('autor')->nullable(); // Puede ser nombre del cliente o usuario
            $table->string('email')->nullable(); // Email del que comenta
            $table->string('tipo_autor')->default('cliente'); // 'cliente' o 'usuario_interno'
            $table->bigInteger('idClientes')->nullable()->unsigned(); // Referencia al cliente si viene del portal
            $table->bigInteger('idUsuario')->nullable()->unsigned(); // Referencia al usuario si es interno
            $table->timestamps();

            // Sin restricción de clave foránea por ahora, ya que Reportes puede tener estructura distinta
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios_reporte');
    }
};
