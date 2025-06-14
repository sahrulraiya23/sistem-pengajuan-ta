<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan status baru ke dalam list ENUM
        DB::statement("ALTER TABLE judul_ta CHANGE COLUMN status status ENUM('draft','submitted','approved','rejected','finalized','menunggu_review_revisi','revisi_diterima') NOT NULL DEFAULT 'submitted'");
    }

    public function down(): void
    {
        // Kembalikan ke kondisi semula jika di-rollback
        DB::statement("ALTER TABLE judul_ta CHANGE COLUMN status status ENUM('draft','submitted','approved','rejected','finalized') NOT NULL DEFAULT 'submitted'");
    }
};
