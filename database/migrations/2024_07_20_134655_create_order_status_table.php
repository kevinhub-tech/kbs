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
        Schema::create('order_status', function (Blueprint $table) {
            $table->uuid('order_id');
            $table->foreign('order_id')->references('order_id')->on('orders')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('status', ['packing', 'hand-over', 'shipped', 'delivering', 'completed']);
            $table->integer('sequence');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status');
    }
};
