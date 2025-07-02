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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('house_address');
            $table->string('unit_floor');
            $table->string('recipient_name');
            $table->bigInteger('phone');
            $table->string('type')->default('Home');
            $table->enum('status', ['shipping', 'arrived', 'inBorder'])->default('shipping');
            $table->enum('payment', ['cash', 'bank']);
            $table->unsignedBigInteger('user_id');
            $table->json('product_ids');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
