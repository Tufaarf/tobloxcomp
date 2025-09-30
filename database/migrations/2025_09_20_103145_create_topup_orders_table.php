<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('topup_orders', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->unsignedBigInteger('roblox_user_id')->nullable();
            $table->string('avatar_url')->nullable();

            $table->unsignedInteger('robux_amount');
            $table->unsignedInteger('base_price');
            $table->decimal('tax_rate', 5, 2);
            $table->unsignedInteger('tax_amount');
            $table->unsignedInteger('total_price');

            $table->string('payment_method');    // qris|gopay|seabank
            $table->string('pay_to');            // nomor / path gambar (disimpan teks)
            $table->string('pay_to_type');       // text|image
            $table->string('wa_number');

            $table->text('payment_proof_text')->nullable(); // bukti pembayaran dalam bentuk teks/link/catatan

            $table->string('status')->default('pending');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topup_orders');
    }
};
