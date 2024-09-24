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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code');
            $table->integer('uses_count');
            $table->string('status');
            $table->string('discount_type');
            $table->integer('discount_value');
            $table->date('expiry_date');
            $table->timestamps();
        });

        Schema::create('orders_coupon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons');
            $table->foreignId('order_id')->constrained('orders');
        });

        Schema::create('products_discount', function (Blueprint $table) {
           $table->id();
           $table->string('status');
           $table->string('discount_type');
           $table->integer('discount_value');
           $table->date('expiry_date');
           $table->foreignId('product_id')->constrained('products'); 
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_coupon');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('products_discount');
    }
};
