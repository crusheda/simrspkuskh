<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * How to Migrate Only This???
     * Run this command = php artisan migrate --path=/database/migrations/2026_01_09_142050_create_ai_klaim_kb.php
     */
    public function up(): void
    {
        Schema::create('simrspku_klaim.ai_klaim_kb', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            $table->string('judul');
            $table->text('konten');
            $table->boolean('aktif')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simrspku_klaim.ai_klaim_kb');
    }
};
