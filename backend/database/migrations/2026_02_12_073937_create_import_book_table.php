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
        Schema::create('import_book', function (Blueprint $table) {
            $table->id('import_book_id');
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('publisher_id');
            $table->integer('stock');
            $table->integer('spoiled');
            $table->integer('usable')->storedAs('stock - spoiled');
            $table->string('image')->nullable();
            $table->dateTime('stocked_at');

            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade');
            $table->foreign('publisher_id')->references('publisher_id')->on('publishers')->onDelete('cascade');

            $table->timestamps();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_book');
    }
};
