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
        Schema::create('__logs', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('route');
            $table->string('code');
            $table->text('message');

            /** Data extracted from request in json format */
            $table->json('data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('__logs');
    }
};