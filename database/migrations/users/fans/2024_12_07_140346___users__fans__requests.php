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
        Schema::create('users__fans__requests', function (Blueprint $table) {
            $table->unsignedBigInteger('from');
            $table->foreign('from')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('to');
            $table->foreign('to')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->string('status', 10)->default('pending');     // pending | accepted | denied

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users__fans__requests');
    }
};
