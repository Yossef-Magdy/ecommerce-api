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
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->integer('total_products')->default(0);
            $table->integer('total_categories')->default(0);
            $table->integer('total_orders')->default(0);
            $table->integer('total_earning')->default(0);
            $table->integer('total_refunded')->default(0);
            $table->integer('total_users')->default(1);
            $table->integer('today_orders')->default(0);
            $table->integer('month_orders')->default(0);
            $table->integer('year_orders')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};
