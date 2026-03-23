<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanSementara extends Model
{
    protected $fillable = [
        'siswa_id',
        'ujian_id',
        'soal_id',
        'jawaban',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}
