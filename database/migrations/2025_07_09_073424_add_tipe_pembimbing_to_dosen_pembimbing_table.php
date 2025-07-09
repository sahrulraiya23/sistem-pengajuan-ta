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
        Schema::table('dosen_pembimbing', function (Blueprint $table) {
            // Menambahkan kolom 'tipe_pembimbing' setelah kolom 'dosen_id'
            $table->string('tipe_pembimbing')->after('dosen_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen_pembimbing', function (Blueprint $table) {
            // Logika untuk menghapus kolom jika migration di-rollback
            $table->dropColumn('tipe_pembimbing');
        });
    }
};
