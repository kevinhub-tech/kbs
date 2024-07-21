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
        Schema::create('discounts', function (Blueprint $table) {
            $table->uuid('discount_id')->primary();
            $table->integer('discount_percentage');
            $table->uuid('created_by');
            $table->foreign('created_by')->references('user_id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->uuid('updated_by');
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
        Schema::dropIfExists('discounts');
    }
};
