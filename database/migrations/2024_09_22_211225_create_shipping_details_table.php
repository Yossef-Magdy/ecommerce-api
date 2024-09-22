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
            $table->string('name');
        });
        Schema::create('shipping_details', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('address');
            $table->string('apartment');
            $table->string('postal_code');
            $table->string('phone_number');
            $table->unsignedBigInteger('governorate_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('governorate_id')->references('id')->on('governorates'); // what will happend on delete and update?
            $table->foreign('user_id')->references('id')->on('users'); // what will happend on delete and update?
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
