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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('order_id')->primary();
            $table->string('order_number', 300);
            $table->enum('payment_method', ['cod', 'debit/credit']);
            $table->boolean('refund_state');
            $table->boolean('is_cancelled');
            $table->double('total');
            $table->uuid('address_id')->nullable();
            $table->foreign('address_id')->references('address_id')->on('user_addresses')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('billing_address_id')->nullable();
            $table->foreign('billing_address_id')->references('address_id')->on('user_addresses')->cascadeOnUpdate()->nullOnDelete();
            $table->uuid('created_by');
            $table->foreign('created_by')->references('user_id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
