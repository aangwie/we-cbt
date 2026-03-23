<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $fillable = [
        'judul',
        'guru_id',
        'token',
        'is_active',
        'durasi',
        'kelas',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function soals()
    {
        return $this->hasMany(Soal::class);
    }

    public function hasilUjians()
    {
        return $this->hasMany(HasilUjian::class);
    }
}
