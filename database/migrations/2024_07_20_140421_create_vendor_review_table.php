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
        Schema::create('vendor_review', function (Blueprint $table) {
            $table->uuid('vendor_id');
            $table->foreign('vendor_id')->references('user_id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('rating');
            $table->mediumText('review');
            $table->uuid('reviewed_by');
            $table->foreign('reviewed_by')->references('user_id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_review');
    }
};
