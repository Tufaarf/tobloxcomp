<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('admin_fee_percent');
            $table->string('code')->unique()->nullable();
            $table->string('type')->default('transfer'); // transfer, qris
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('qris_image')->nullable();
        });
    }

    public function down(): void {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->decimal('admin_fee_percent', 5, 2)->nullable();
            $table->dropColumn(['code', 'type', 'bank_name', 'account_number', 'account_holder_name', 'qris_image']);
        });
    }
};
