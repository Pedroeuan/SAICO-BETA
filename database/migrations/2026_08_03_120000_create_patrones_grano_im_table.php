<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Crea el catálogo reutilizable de imágenes maestras para tamaño de grano. */
    public function up(): void
    {
        if (Schema::hasTable('patrones_grano_im')) {
            return;
        }

        Schema::create('patrones_grano_im', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->decimal('valor_grano', 4, 1)->unique();
            $table->string('ruta_imagen', 500);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrones_grano_im');
    }
};
