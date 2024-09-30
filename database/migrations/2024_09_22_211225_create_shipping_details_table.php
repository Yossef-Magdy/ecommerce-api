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
        Schema::create('governorates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });
        Schema::create('shipping_details', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('address');
            $table->string('apartment');
            $table->string('postal_code');
            $table->string('phone_number');
            $table->boolean('is_default')->default(false);
            $table->foreignId('governorate_id')->constrained('governorates')->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_details');
        Schema::dropIfExists('governorates');
    }
};
