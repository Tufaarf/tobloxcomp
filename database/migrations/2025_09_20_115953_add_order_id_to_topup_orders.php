<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('topup_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('topup_orders', 'order_id')) {
                // 20 char cukup untuk RBX-YYMMDD-ABCDE
                $table->string('order_id', 20)->unique()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('topup_orders', function (Blueprint $table) {
            if (Schema::hasColumn('topup_orders', 'order_id')) {
                $table->dropColumn('order_id');
            }
        });
    }
};
