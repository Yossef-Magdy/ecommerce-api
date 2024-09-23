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
            $table->id();
            $table->decimal('paid_amount', 10, 2);
            $table->decimal('outstanding_amount', 10, 2);
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });

        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->string("method");
            $table->string("status");
            $table->foreignId('order_id')->constrained();
        });

        Schema::create('shipping', function (Blueprint $table) {
            $table->id();
            $table->string("method");
            $table->string("status");
            $table->decimal('fee', 10, 2);
            $table->foreignId('order_id')->constrained();
            $table->foreignId('shipping_details_id')->constrained();
            $table->timestamps(); 
        });

        Schema::create('order_item', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);
            $table->foreignId('order_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('payment');
        Schema::dropIfExists('shipping');
        Schema::dropIfExists('order_item');
    }
};
