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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('api_token', 64)->unique();
            $table->string('restart_pin', 10)->nullable();

            /* Other user info */
            $table->string('city', 100)->nullable();
            $table->integer('country')->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('prefix')->default(21)->nullable();
            $table->string('phone', 30)->nullable();


            /* Active or banned */
            $table->string('role', 15)->default("user");
            $table->tinyInteger('active')->default(1);

            /* User settings */
            $table->tinyInteger('s_not')->default(1);       // Show notifications : true | false
            $table->tinyInteger('s_loc')->default(1);       // Show location : true | false
            $table->tinyInteger('s_b_date')->default(1);    // Show birth date : true | false

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
