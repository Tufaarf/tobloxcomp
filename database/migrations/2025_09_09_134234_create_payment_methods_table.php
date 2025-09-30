<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // ex: QRIS, Bank Transfer
            $table->decimal('admin_fee_percent', 5, 2)->nullable(); // ex: 5.00 (%)
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('payment_methods');
    }
};
