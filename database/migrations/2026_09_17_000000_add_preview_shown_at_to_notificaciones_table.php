<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Forward-only, additive change. This migration is deliberately not run by
     * this implementation; it must be applied by the deployment owner.
     */
    public function up(): void
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->timestamp('preview_shown_at')->nullable()->after('leida');
            $table->index(['users_id', 'preview_shown_at', 'created_at'], 'notificaciones_preview_lookup_idx');
        });
    }

    /**
     * No destructive rollback is supplied to protect production data.
     */
    public function down(): void
    {
        // Intentionally left empty.
    }
};
