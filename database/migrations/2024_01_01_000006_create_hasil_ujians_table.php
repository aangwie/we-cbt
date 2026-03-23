<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('ujian_id')->constrained('ujians')->onDelete('cascade');
            $table->integer('jumlah_benar');
            $table->decimal('nilai', 5, 2);
            $table->datetime('tgl_selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_ujians');
    }
};
