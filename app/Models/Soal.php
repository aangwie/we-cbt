<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $fillable = [
        'ujian_id',
        'paket_soal_id',
        'mapel_id',
        'kelas',
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

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function paketSoal()
    {
        return $this->belongsTo(PaketSoal::class);
    }
}
