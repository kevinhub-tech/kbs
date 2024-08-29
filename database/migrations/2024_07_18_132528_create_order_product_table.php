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
        Schema::create('ordered_book', function (Blueprint $table) {
            $table->uuid('order_id');
            $table->foreign('order_id')->references('order_id')->on('orders');
            $table->uuid('book_id');
            $table->foreign('book_id')->references('book_id')->on('books');
            $table->integer('quantity');
            $table->double('ordered_book_price');
            $table->double('ordered_book_delivery_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordered_book');
    }
};
