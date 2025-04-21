<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_surat_ta_table.php
    public function up(): void
    {
        Schema::create('surat_ta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('judul_ta_id')->constrained('judul_ta')->onDelete('cascade');
            $table->string('nomor_surat');
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_ta');
    }
};
