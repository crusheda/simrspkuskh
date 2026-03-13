<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * How to Migrate Only This???
     * Run this command = php artisan migrate --path=/database/migrations/2026_03_13_102351_create_table_rating.php
     */
    public function up(): void
    {
        Schema::create('simrspku_klaim.rating', function (Blueprint $table) {
            $table->id();
            $table->integer('rating')->comment('1-5');
            $table->longText('respon')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simrspku_klaim.rating');
    }
};
