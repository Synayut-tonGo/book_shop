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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('order_id');
            $table->enum('method', ['qr', 'cash']);
            $table->decimal('amount', 10, 2);
            $table->dateTime('paid_datetime');
            $table->enum('status', ['paid', 'unpaid', 'refunded'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Foreign key
            $table->foreign('order_id')
                  ->references('order_id')
                  ->on('orders')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
