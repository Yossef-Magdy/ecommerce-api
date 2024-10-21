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
        Schema::table('analytics', function (Blueprint $table) {
            $table->integer('total_users')->default(0)->change();

            $table->dropColumn('today_orders');
            $table->dropColumn('month_orders');
            $table->dropColumn('year_orders');
            $table->dropColumn('total_categories');
            $table->dropColumn('total_products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analytics', function (Blueprint $table) {
            $table->integer('total_users')->default(1)->change();
            $table->integer('today_orders')->default(0);
            $table->integer('month_orders')->default(0);
            $table->integer('year_orders')->default(0); 
            $table->integer('total_categories')->default(0);
            $table->integer('total_products')->default(0);
        });
    }
};
