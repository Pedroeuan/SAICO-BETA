<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cambio aditivo: las notificaciones existentes se conservan como normales
     * para que nunca generen una avalancha de previews al desplegar esta mejora.
     */
    public function up(): void
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->string('prioridad', 10)->default('normal')->after('leida');
            $table->index(['users_id', 'leida', 'prioridad', 'preview_shown_at', 'created_at'], 'notificaciones_preview_priority_idx');
        });
    }

    /** No se elimina información ni estructura automáticamente. */
    public function down(): void
    {
        // Intencionalmente vacío.
    }
};
