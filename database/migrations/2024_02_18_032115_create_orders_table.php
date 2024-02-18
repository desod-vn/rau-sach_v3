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
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->integer('status')->default(0);
            $table->unsignedBigInteger('total');
            $table->foreignId('shipper')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->unsignedInteger('shipping_fee')->default(0);
            $table->string('coupon')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
