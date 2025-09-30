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
        Schema::create('detailed_services', function (Blueprint $table) {
           $table->id();
            $table->string('name');            // nama service
            $table->longText('detail')->nullable(); // rich text detail
            $table->string('image')->nullable();    // path gambar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailed_services');
    }
};
