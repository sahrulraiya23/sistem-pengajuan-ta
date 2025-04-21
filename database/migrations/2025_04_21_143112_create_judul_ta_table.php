<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_judul_ta_table.php
    public function up(): void
    {
        Schema::create('judul_ta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul1');
            $table->string('judul2');
            $table->string('judul3');
            $table->string('judul_approved')->nullable();
            $table->string('judul_revisi')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'finalized'])->default('submitted');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judul_ta');
    }
};
