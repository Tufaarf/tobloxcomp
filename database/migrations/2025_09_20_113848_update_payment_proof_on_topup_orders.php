<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('topup_orders', function (Blueprint $table) {
            if (Schema::hasColumn('topup_orders', 'payment_proof_text')) {
                $table->dropColumn('payment_proof_text');
            }
            if (! Schema::hasColumn('topup_orders', 'payment_proof_path')) {
                $table->string('payment_proof_path')->nullable()->after('wa_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('topup_orders', function (Blueprint $table) {
            if (Schema::hasColumn('topup_orders', 'payment_proof_path')) {
                $table->dropColumn('payment_proof_path');
            }
            if (! Schema::hasColumn('topup_orders', 'payment_proof_text')) {
                $table->text('payment_proof_text')->nullable();
            }
        });
    }
};
