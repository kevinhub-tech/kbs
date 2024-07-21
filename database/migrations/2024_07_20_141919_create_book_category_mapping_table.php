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
        Schema::create('book_categories', function (Blueprint $table) {
            $table->uuid('category_id');
            $table->foreign('category_id')->references('category_id')->on('categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->uuid('book_id');
            $table->foreign('book_id')->references('book_id')->on('books')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_categories');
    }
};
