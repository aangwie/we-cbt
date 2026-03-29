<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'name',
        'tanggal_lahir',
        'jenis_kelamin',
        'kelas',
        'nisn',
        'is_logged_in',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'is_logged_in' => 'boolean',
        ];
    }

    public function hasilUjians()
    {
        return $this->hasMany(HasilUjian::class);
    }

    public function sesiUjians()
    {
        return $this->hasMany(SesiUjian::class);
    }
}
