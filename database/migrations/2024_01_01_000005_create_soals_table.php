<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujians')->onDelete('cascade');
            $table->text('teks_soal');
            $table->string('gambar_soal')->nullable();
            $table->text('pilihan_a');
            $table->text('pilihan_b');
            $table->text('pilihan_c');
            $table->text('pilihan_d');
            $table->text('pilihan_e');
            $table->string('gambar_a')->nullable();
            $table->string('gambar_b')->nullable();
            $table->string('gambar_c')->nullable();
            $table->string('gambar_d')->nullable();
            $table->string('gambar_e')->nullable();
            $table->enum('jawaban_benar', ['a', 'b', 'c', 'd', 'e']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soals');
    }
};
