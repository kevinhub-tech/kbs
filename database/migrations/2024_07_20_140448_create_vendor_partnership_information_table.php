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
        Schema::create('vendor_partnership_informations', function (Blueprint $table) {
            $table->uuid('vendor_info_id')->primary();
            $table->uuid('vendor_application_id');
            $table->foreign('vendor_application_id')->references('application_id')->on('vendor_partnership_applications')->cascadeOnUpdate()->cascadeOnDelet();
            $table->string('email', 200);
            $table->string('phone_number', 200);
            $table->longText('vendor_description');
            $table->mediumText('facebook_link');
            $table->mediumText('instagram_link');
            $table->mediumText('youtube_link');
            $table->mediumText('x_link');
            $table->mediumText('other_link');
            $table->uuid('vendor_id');
            $table->foreign('vendor_id')->references('user_id')->on('users')->cascadeOnUpdate()->cascadeOnDelete()->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_partnership_informations');
    }
};
