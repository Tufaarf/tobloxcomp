<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePointFromAboutsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            // Hapus kolom 'point'
            if (Schema::hasColumn('abouts', 'point')) {
                $table->dropColumn('point');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            // Tambal kembali kolom 'point' jika diperlukan
            if (! Schema::hasColumn('abouts', 'point')) {
                $table->string('point'); // sesuaikan tipe dan nullability sesuai aslinya
            }
        });
    }
}
