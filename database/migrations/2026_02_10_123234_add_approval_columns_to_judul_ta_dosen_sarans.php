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
    Schema::table('judul_ta_dosen_sarans', function (Blueprint $table) {
        // Cek dulu apakah kolom belum ada, baru buat
        if (!Schema::hasColumn('judul_ta_dosen_sarans', 'judul_pilihan')) {
            $table->string('judul_pilihan')->nullable();
        }
        if (!Schema::hasColumn('judul_ta_dosen_sarans', 'catatan')) {
            $table->text('catatan')->nullable();
        }
        if (!Schema::hasColumn('judul_ta_dosen_sarans', 'status_persetujuan')) {
            $table->enum('status_persetujuan', ['pending', 'approved', 'rejected'])->default('pending');
        }
    });
}

public function down(): void
{
    Schema::table('judul_ta_dosen_sarans', function (Blueprint $table) {
        $table->dropColumn(['judul_pilihan', 'catatan', 'status_persetujuan']);
    });
}
};
