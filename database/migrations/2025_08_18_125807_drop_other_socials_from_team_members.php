<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            // Hapus kolom sosmed selain instagram
            if (Schema::hasColumn('team_members', 'twitter')) {
                $table->dropColumn('twitter');
            }
            if (Schema::hasColumn('team_members', 'facebook')) {
                $table->dropColumn('facebook');
            }
            if (Schema::hasColumn('team_members', 'linkedin')) {
                $table->dropColumn('linkedin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            // Kembalikan kolom jika di-rollback
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
        });
    }
};
