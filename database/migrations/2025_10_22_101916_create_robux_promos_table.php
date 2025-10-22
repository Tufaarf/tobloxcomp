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
        Schema::create('robux_promos', function (Blueprint $table) {
            $table->id();

            // Minimal Robux purchase to trigger the promo
            $table->integer('min_purchase_amount');

            // The promo price for the minimum amount
            // Store in the smallest currency unit (e.g., cents, or just Rupiah)
            $table->bigInteger('promo_price');

            // Maximum Robux purchase allowed for this promo
            $table->integer('max_purchase_amount');

            // Status to enable/disable this promo
            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('robux_promos');
    }
};
