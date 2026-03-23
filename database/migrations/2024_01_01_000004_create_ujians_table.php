<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujians', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->string('token', 10)->unique();
            $table->boolean('is_active')->default(false);
            $table->integer('durasi'); // in minutes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujians');
    }
};
