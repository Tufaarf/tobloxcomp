<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->foreignId('account_product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('game_id')->nullable()->constrained()->nullOnDelete();

            // Snapshot
            $table->string('account_name');
            $table->decimal('price', 15, 2);

            // Customer
            $table->string('name');
            $table->string('email');
            $table->string('phone');

            // Payment
            $table->string('payment_method');
            $table->decimal('total_price', 15, 2);
            $table->string('status')->default('review');
            $table->string('payment_proof')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_orders');
    }
};
