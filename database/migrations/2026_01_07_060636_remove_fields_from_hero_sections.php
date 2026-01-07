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
        Schema::table('hero_sections', function (Blueprint $table) {
            // Menghapus kolom menggunakan array
            $table->dropColumn(['description', 'button_text', 'button_url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_sections', function (Blueprint $table) {
            // Mengembalikan kolom jika di-rollback (sesuai tipe data awal)
            // Saya tambahkan ->nullable() untuk keamanan jika ada data lama,
            // tapi jika ingin persis seperti awal, hapus ->nullable()

            $table->text('description')->nullable()->after('title');
            $table->string('button_text')->nullable()->after('description');
            $table->string('button_url')->nullable()->after('button_text');
        });
    }
};
