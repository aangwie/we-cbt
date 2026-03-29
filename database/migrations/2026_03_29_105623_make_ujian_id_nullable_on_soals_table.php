<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('soals', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE soals MODIFY ujian_id BIGINT UNSIGNED NULL;');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soals', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE soals MODIFY ujian_id BIGINT UNSIGNED NOT NULL;');
        });
    }
};
