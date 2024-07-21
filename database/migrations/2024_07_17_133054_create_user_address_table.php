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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->uuid('address_id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->mediumText('address');
            $table->string('state', 200);
            $table->string('phone_number', 200);
            $table->integer('postal_code');
            $table->boolean('default')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
