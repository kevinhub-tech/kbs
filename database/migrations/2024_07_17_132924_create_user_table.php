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
            $table->uuid('user_id')->primary();
            $table->string('name', 200);
            $table->string('email', 200)->nullable();
            $table->text('image')->nullable();
            $table->boolean('is_auth')->default(false);
            $table->string('token', 100)->nullable();
            $table->string('password', 300)->nullable();
            $table->uuid('role_id')->nullable();
            $table->foreign('role_id')->references('role_id')->on('roles')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
