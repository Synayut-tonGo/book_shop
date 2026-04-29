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
        Schema::create('book_image', function (Blueprint $table) {
            $table->id('book_image_id');
            $table->unsignedBigInteger('book_id');
            $table->string('image');
            $table->enum('type', ['cover' , 'profile']);
            $table->dateTime('upload_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_image');
    }
};
