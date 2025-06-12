<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['mahasiswa', 'dosen', 'kajur'])->after('password');
            }
            if (!Schema::hasColumn('users', 'nomor_induk')) {
                $table->string('nomor_induk')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'program_studi')) {
                $table->string('program_studi')->nullable()->after('nomor_induk');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'nomor_induk')) {
                $table->dropColumn('nomor_induk');
            }
            if (Schema::hasColumn('users', 'program_studi')) {
                $table->dropColumn('program_studi');
            }
        });
    }
};
