<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Kontak & Roblox
            $table->string('wa_number');
            $table->string('roblox_username');
            $table->unsignedBigInteger('roblox_user_id')->nullable();

            // Experience (dari API Roblox)
            $table->unsignedBigInteger('experience_id')->nullable();
            $table->string('experience_name')->nullable();

            // Produk & Robux
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('robux_amount')->nullable();      // jumlah robux yang dipesan
            $table->integer('pack_size')->default(50);        // 50 Robux
            $table->integer('pack_price')->default(14000);    // Rp 14.000 / 50 Robux

            // Pembayaran
            $table->foreignId('payment_method_id')->constrained()->cascadeOnDelete();
            $table->decimal('admin_fee_percent', 5, 2)->nullable();
            $table->integer('admin_fee_amount')->default(0);

            // Total
            $table->integer('subtotal')->default(0);
            $table->integer('total')->default(0);

            // Status & approval
            $table->enum('status', ['pending','approved','rejected','paid','canceled'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->json('meta')->nullable(); // simpan payload/respon API
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
