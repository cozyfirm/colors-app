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
        Schema::create('__other_data', function (Blueprint $table) {
            $table->id();

            $table->string('type', '100')->default('splash');
            $table->string('title');
            $table->text('content')->nullable();
            $table->integer('file_id')->nullable();

            $table->integer('views')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('__other_data');
    }
};
