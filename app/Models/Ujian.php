<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $fillable = [
        'judul',
        'mapel_id',
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

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function hasilUjians()
    {
        return $this->hasMany(HasilUjian::class);
    }
}
