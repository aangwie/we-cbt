<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilUjian extends Model
{
    protected $fillable = [
        'siswa_id',
        'ujian_id',
        'jumlah_benar',
        'nilai',
        'tgl_selesai',
    ];

    protected function casts(): array
    {
        return [
            'tgl_selesai' => 'datetime',
            'nilai' => 'decimal:2',
        ];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}
