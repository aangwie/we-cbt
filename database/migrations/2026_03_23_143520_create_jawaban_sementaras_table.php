<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_sementaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('ujian_id')->constrained('ujians')->cascadeOnDelete();
            $table->foreignId('soal_id')->constrained('soals')->cascadeOnDelete();
            $table->enum('jawaban', ['a', 'b', 'c', 'd', 'e']);
            $table->timestamps();

            $table->unique(['siswa_id', 'ujian_id', 'soal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_sementaras');
    }
};
