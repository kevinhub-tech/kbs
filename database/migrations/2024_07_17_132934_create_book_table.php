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
        Schema::create('books', function (Blueprint $table) {
            $table->uuid('book_id')->primary();
            $table->mediumText('book_name');
            $table->longText('book_desc');
            $table->string('author_name', 255);
            $table->text('image');
            $table->double('price');
            $table->double('delivery_fee');
            $table->uuid('discount_id')->nullable();
            $table->foreign('discount_id')->references('discount_id')->on("discounts");
            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')->references('user_id')->on("users")->cascadeOnUpdate()->cascadeOnDelete();
            $table->uuid('updated_by')->nullable();
            $table->foreign('updated_by')->references('user_id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
