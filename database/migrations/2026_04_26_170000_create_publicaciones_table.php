<?php

use App\Enums\TipoPublicacion;
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
        Schema::create('publicaciones', function (Blueprint $table): void {
            $table->id();
            $table->char('uuid', 36)->unique()->index();
            $table->string('titulo', 150);
            $table->string('slug', 160)->unique();
            $table->text('contenido');
            $table->enum('tipo', array_column(TipoPublicacion::cases(), 'value'));
            $table->string('imagen', 500)->nullable();
            $table->string('imagen_alt', 200)->nullable();
            $table->string('video', 500)->nullable();
            $table->string('url_destino', 500)->nullable();
            $table->json('redes_objetivo');
            $table->json('resultado_publicacion')->nullable();
            $table->boolean('publicado_en_redes')->default(false);
            $table->timestamp('publicado_at')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tipo', 'activo']);
            $table->index(['publicado_en_redes', 'activo']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicaciones');
    }
};
