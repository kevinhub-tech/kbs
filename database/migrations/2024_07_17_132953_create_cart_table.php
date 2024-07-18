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
        Schema::create('user_cart', function (Blueprint $table) {
            $table->uuid('user_id')->reference('user_id')->on('users');
            $table->uuid('book_id')->reference('book_id')->on('books');
            $table->integer('quantity');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
