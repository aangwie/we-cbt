<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $fillable = [
        'ujian_id',
        'teks_soal',
        'gambar_soal',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'pilihan_e',
        'gambar_a',
        'gambar_b',
        'gambar_c',
        'gambar_d',
        'gambar_e',
        'jawaban_benar',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}
