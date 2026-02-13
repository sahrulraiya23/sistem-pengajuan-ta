<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thesis', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('abstract');
            $table->enum('type', ['skripsi', 'tesis', 'disertasi']);
            $table->string('author');
            $table->string('program_study');
            $table->year('year');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thesis');
    }
};