<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->string('header');
            $table->longText('description')->nullable();   // rich editor
            $table->string('link_whatsapp')->nullable();
            $table->string('link_instagram')->nullable();
            $table->string('link_discord')->nullable();
            $table->boolean('is_active')->default(true);   // optional: bisa nonaktifkan section
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('communities');
    }
};
