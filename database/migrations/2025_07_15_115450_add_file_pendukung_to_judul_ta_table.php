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
        Schema::table('judul_ta', function (Blueprint $table) {
            $table->string('file_pendukung1')->nullable()->after('judul_revisi');
            $table->string('file_pendukung2')->nullable()->after('file_pendukung1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('judul_ta', function (Blueprint $table) {
            $table->dropColumn('file_pendukung1');
            $table->dropColumn('file_pendukung2');
        });
    }
};