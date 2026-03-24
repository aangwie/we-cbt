<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('soals', function (Blueprint $table) {
            $table->foreignId('mapel_id')->nullable()->constrained('mapels')->nullOnDelete();
            $table->string('kelas', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('soals', function (Blueprint $table) {
            $table->dropForeign(['mapel_id']);
            $table->dropColumn(['mapel_id', 'kelas']);
        });
    }
};
