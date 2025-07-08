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
        // Hapus kolom dosen_saran_id dari tabel judul_ta jika masih ada
        Schema::table('judul_ta', function (Blueprint $table) {
            if (Schema::hasColumn('judul_ta', 'dosen_saran_id')) {
                $table->dropConstrainedForeignId('dosen_saran_id');
            }
        });

        // Buat tabel pivot
        Schema::create('judul_ta_dosen_sarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('judul_ta_id')->constrained('judul_ta')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID Dosen
            $table->timestamps();

            // Memastikan kombinasi judul_ta_id dan user_id unik untuk mencegah duplikasi
            $table->unique(['judul_ta_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judul_ta_dosen_sarans');

        // Opsional: Jika Anda ingin mengembalikan kolom dosen_saran_id saat rollback
        // Schema::table('judul_ta', function (Blueprint $table) {
        //     $table->foreignId('dosen_saran_id')->nullable()->constrained('users')->onDelete('set null');
        // });
    }
};
