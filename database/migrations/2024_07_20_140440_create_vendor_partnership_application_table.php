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
        Schema::create('vendor_partnership_applications', function (Blueprint $table) {
            $table->uuid('application_id')->primary();
            $table->string('email', 200);
            $table->longText('application_letter');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->mediumText('rejection_reason');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_partnership_applications');
    }
};
