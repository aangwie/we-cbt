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
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function hasilUjians()
    {
        return $this->hasMany(HasilUjian::class);
    }
}
