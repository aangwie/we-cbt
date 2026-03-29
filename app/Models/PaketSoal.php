<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketSoal extends Model
{
    protected $fillable = ['mapel_id', 'kelas', 'judul', 'deskripsi'];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function soals()
    {
        return $this->hasMany(Soal::class);
    }
}
