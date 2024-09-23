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
        Schema::create('copons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code');
            $table->integer('uses_count');
            $table->string('status');
            $table->string('discount_type');
            $table->integer('discount_value');
            $table->date('expiry_date');
            $table->timestamps();
        });

        Schema::create('orders_copon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('copon_id')->constrained();
            $table->foreignId('order_id')->constrained();
        });

        Schema::create('products_discount', function (Blueprint $table) {
           $table->id();
           $table->string('status');
           $table->string('discount_type');
           $table->integer('discount_value');
           $table->date('expiry_date');
           $table->foreignId('product_id')->constrained(); 
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('copons');
        Schema::dropIfExists('orders_copon');
        Schema::dropIfExists('products_discount');
    }
};
