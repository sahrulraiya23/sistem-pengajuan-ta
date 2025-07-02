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
            // Menambahkan kolom baru untuk menyimpan ID dosen saran
            $table->foreignId('dosen_saran_id')->nullable()->constrained('users')->onDelete('set null')->after('mahasiswa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('judul_ta', function (Blueprint $table) {
            // Perintah untuk menghapus kolom jika migrasi di-rollback
            $table->dropForeign(['dosen_saran_id']);
            $table->dropColumn('dosen_saran_id');
        });
    }
};
