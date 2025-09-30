<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDescriptionAndSubDescriptionTypeInAboutsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // pastikan doctrine/dbal sudah terinstall: composer require doctrine/dbal
        Schema::table('abouts', function (Blueprint $table) {
            // Ubah 'description' dan 'sub_description' menjadi MEDIUMTEXT
            $table->text('description')->change();
            $table->text('sub_description')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            // Kembalikan ke TEXT semula
            $table->string('description')->change();
            $table->string('sub_description')->change();
        });
    }
}
