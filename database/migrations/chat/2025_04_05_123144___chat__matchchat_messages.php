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
        Schema::create('chat__matchchat_messages', function (Blueprint $table) {
            $table->id();

            /** Chat ID */
            $table->unsignedBigInteger('chat_id')->nullable();
            $table->foreign('chat_id')
                ->references('id')
                ->on('chat__matchchat')
                ->onDelete('cascade');

            /** User that created message */
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            /** Chat message */
            $table->string('message')->nullable();

            /** In case of image, file ID */
            $table->unsignedBigInteger('file_id')->nullable();
            $table->foreign('file_id')
                ->references('id')
                ->on('__files')
                ->onDelete('cascade');

            $table->integer('likes')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat__matchchat_messages');
    }
};
