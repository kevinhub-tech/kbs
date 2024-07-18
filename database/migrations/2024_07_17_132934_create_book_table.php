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
        Schema::create('book', function (Blueprint $table) {
            $table->uuid('book_id');
            $table->mediumText('book_name');
            $table->longText('book_desc');
            $table->string('author_name', 255);
            $table->double('price');
            $table->uuid('created_by')->reference('users_id')->on("users");
            $table->uuid('updated_by')->reference('user_id')->on('users')->nullable();
            $table->timestamps('created_at');
            $table->timestamps('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book');
    }
};
