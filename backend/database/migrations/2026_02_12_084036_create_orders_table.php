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

                $table->id('order_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('address_id')->nullable();

                $table->decimal('subtotal', 10, 2);
                $table->decimal('total_discount', 10, 2);
                $table->decimal('total_amount', 10, 2);

                $table->dateTime('order_date');

                $table->enum('status', [
                    'pending',
                    'processing',
                    'completed',
                    'cancelled',
                    'returned'
                ])->default('pending');

                $table->timestamps();

                $table->foreign('user_id')
                    ->references('user_id')
                    ->on('users')
                    ->onDelete('cascade');

                $table->foreign('address_id')
                    ->references('address_id')
                    ->on('addresses')
                    ->onDelete('set null');
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
