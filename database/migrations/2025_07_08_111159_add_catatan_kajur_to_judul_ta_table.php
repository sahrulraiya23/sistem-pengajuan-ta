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
            // Tambahkan kolom catatan_kajur sebagai string, nullable
            // Anda bisa menyesuaikan tipe data dan properti lainnya sesuai kebutuhan
            $table->text('catatan_kajur')->nullable()->after('alasan_penolakan'); // Contoh penempatan setelah alasan_penolakan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('judul_ta', function (Blueprint $table) {
            // Hapus kolom catatan_kajur jika migrasi di-rollback
            $table->dropColumn('catatan_kajur');
        });
    }
};
