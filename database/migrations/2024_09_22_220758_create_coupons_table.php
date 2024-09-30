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
            $table->integer('uses_count')->default(50);
            $table->enum('status', ['active', 'expired'])->default('active');
            $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed');
            $table->integer('discount_value');
            $table->date('expiry_date')->default(now()->addDays(7));
            $table->timestamps();
        });

        Schema::create('orders_coupon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons');
            $table->foreignId('order_id')->constrained('orders');
        });

        Schema::create('products_discount', function (Blueprint $table) {
           $table->id();
           $table->enum('status', ['active', 'closed'])->default('active');
           $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed');
           $table->integer('discount_value')->default(0);
           $table->date('expiry_date')->default(now()->addWeek());
           $table->foreignId('product_id')->unique()->constrained('products')->cascadeOnDelete(); 
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
