<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSecondImageAndHeadlineInAboutsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            // Tambah kolom headline setelah description
            $table->string('headline')->after('description');
            // Tambah kolom second_image (nullable) setelah image
            $table->string('second_image')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            // Hapus kedua kolom jika rollback
            $table->dropColumn(['second_image', 'headline']);
        });
    }
}
