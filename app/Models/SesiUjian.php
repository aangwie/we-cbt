<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiUjian extends Model
{
    protected $fillable = [
        'siswa_id',
        'ujian_id',
        'started_at',
        'soal_order',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'soal_order' => 'array',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}
