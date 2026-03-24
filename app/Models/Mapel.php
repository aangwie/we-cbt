<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $fillable = ['nama_mapel', 'kode_mapel'];

    public function soals()
    {
        return $this->hasMany(Soal::class);
    }

    public function gurus()
    {
        return $this->belongsToMany(User::class, 'guru_mapel', 'mapel_id', 'user_id');
    }

    public function ujians()
    {
        return $this->hasMany(Ujian::class);
    }
}
