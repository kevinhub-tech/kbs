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
        Schema::create('user_favourites', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->uuid('book_id');
            $table->foreign('book_id')->references('book_id')->on('books')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_favourites');
    }
};
