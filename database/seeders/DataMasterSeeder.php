<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\Ujian;
use App\Models\Soal;
use Illuminate\Support\Str;

class DataMasterSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada Guru
        $guru = User::where('role', 'guru')->first() ?? User::create([
            'name' => 'Guru Demo Master',
            'email' => 'gurumaster@wetest.com',
            'password' => bcrypt('password'),
            'role' => 'guru',
        ]);

        // Buat Data Kelas
        $kelasNames = ['VII', 'VIII', 'IX'];
        foreach ($kelasNames as $k) {
            Kelas::firstOrCreate(['nama_kelas' => $k]);
        }

        // Buat Data Mapel
        $mapelsData = [
            ['kode_mapel' => 'MTK', 'nama_mapel' => 'Matematika'],
            ['kode_mapel' => 'BIN', 'nama_mapel' => 'Bahasa Indonesia'],
            ['kode_mapel' => 'IPA', 'nama_mapel' => 'Ilmu Pengetahuan Alam'],
        ];
        
        $mapelModels = [];
        foreach ($mapelsData as $m) {
            $mapelModels[] = Mapel::firstOrCreate(['kode_mapel' => $m['kode_mapel']], $m);
        }

        // Generate Soal (20 per kelas per mapel)
        foreach ($mapelModels as $mapel) {
            foreach ($kelasNames as $kelas) {
                // Buat Ujian (karena Soal bergantung pada ujian_id)
                $ujian = Ujian::firstOrCreate([
                    'judul' => "Soal Bank - {$mapel->nama_mapel} - Kelas {$kelas}",
                    'guru_id' => $guru->id,
                ], [
                    'token' => strtoupper(Str::random(5)),
                    'is_active' => false, // Nonaktifkan karena hanya bank soal
                    'durasi' => 120, // 120 Menit
                ]);

                // Cek agar tidak ter-duplicate jika dijalankan berkali-kali
                $existingSoals = Soal::where('ujian_id', $ujian->id)->count();
                $soalToGenerate = 20 - $existingSoals;

                if ($soalToGenerate > 0) {
                    for ($i = 0; $i < $soalToGenerate; $i++) {
                        $nomor = $existingSoals + $i + 1;
                        Soal::create([
                            'ujian_id' => $ujian->id,
                            'mapel_id' => $mapel->id,
                            'kelas' => $kelas,
                            'teks_soal' => "Di antara pernyataan berikut, manakah yang merupakan representasi terbaik dari contoh soal nomor {$nomor} untuk mata pelajaran {$mapel->nama_mapel} ({$mapel->kode_mapel}) di tingkat kelas {$kelas}?",
                            'pilihan_a' => "Pilihan Jawaban A yang terstruktur dengan penjelasan logis.",
                            'pilihan_b' => "Pilihan Jawaban B yang merupakan alternatif kedua.",
                            'pilihan_c' => "Pilihan Jawaban C yang sengaja dibuat sedikit mengecoh.",
                            'pilihan_d' => "Pilihan Jawaban D yang salah.",
                            'pilihan_e' => "Pilihan Jawaban E sebagai pengecoh khusus dalam analisis rasional.",
                            'jawaban_benar' => collect(['a', 'b', 'c', 'd', 'e'])->random(),
                        ]);
                    }
                }
            }
        }
        
        $this->command->info('DataMasterSeeder berhasil dijalankan: 3 Kelas, 3 Mapel, dan 180 Soal sukses tereksekusi.');
    }
}
