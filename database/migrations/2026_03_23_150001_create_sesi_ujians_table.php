<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('ujian_id')->constrained('ujians')->cascadeOnDelete();
            $table->timestamp('started_at');
            $table->json('soal_order');
            $table->timestamps();

            $table->unique(['siswa_id', 'ujian_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_ujians');
    }
};
