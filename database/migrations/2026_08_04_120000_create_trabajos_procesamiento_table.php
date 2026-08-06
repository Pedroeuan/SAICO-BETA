<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Guarda el estado durable de Fiji, XRF y la generacion de PDF.
     * La cola puede reiniciarse sin perder la referencia que conserva el navegador.
     */
    public function up(): void
    {
        Schema::create('trabajos_procesamiento', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('usuario_id')->index();
            $table->string('tipo', 40)->index();
            $table->string('estado', 20)->default('pendiente')->index();
            $table->string('mensaje', 255)->nullable();
            $table->json('contexto')->nullable();
            $table->longText('resultado')->nullable();
            $table->text('error')->nullable();
            $table->timestamp('iniciado_at')->nullable();
            $table->timestamp('completado_at')->nullable();
            $table->timestamp('expira_at')->nullable()->index();
            $table->timestamps();

            // No se agrega una llave foranea para que el despliegue no dependa
            // del motor o del nombre historico de la tabla de usuarios.
            $table->index(['usuario_id', 'tipo', 'estado'], 'trabajos_usuario_tipo_estado');
        });
    }

    /** Revierte exclusivamente la tabla creada por esta migracion. */
    public function down(): void
    {
        Schema::dropIfExists('trabajos_procesamiento');
    }
};
