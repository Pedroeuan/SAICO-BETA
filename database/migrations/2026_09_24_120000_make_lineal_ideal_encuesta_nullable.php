<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('Lineal_Ideal', function (Blueprint $table): void {
            $table->integer('idEncuesta')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('Lineal_Ideal', function (Blueprint $table): void {
            $table->integer('idEncuesta')->nullable(false)->change();
        });
    }
};
