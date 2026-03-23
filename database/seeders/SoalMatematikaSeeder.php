<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\User;
use Illuminate\Support\Str;

class SoalMatematikaSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create guru
        $guru = User::where('role', 'guru')->first();

        // Create Ujian Matematika
        $ujian = Ujian::create([
            'judul' => 'Matematika Kelas 7 SMP',
            'guru_id' => $guru->id,
            'token' => strtoupper(Str::random(6)),
            'is_active' => true,
            'durasi' => 90,
        ]);

        $soalData = [
            [
                'teks_soal' => 'Hasil dari -15 + (-12) - (-10) adalah...',
                'pilihan_a' => '-17',
                'pilihan_b' => '-27',
                'pilihan_c' => '-7',
                'pilihan_d' => '17',
                'pilihan_e' => '27',
                'jawaban_benar' => 'a'
            ],
            [
                'teks_soal' => 'Suhu di dalam kulkas -4°C, sedangkan suhu di ruangan 28°C. Selisih suhu di kedua tempat tersebut adalah...',
                'pilihan_a' => '24°C',
                'pilihan_b' => '-32°C',
                'pilihan_c' => '32°C',
                'pilihan_d' => '-24°C',
                'pilihan_e' => '28°C',
                'jawaban_benar' => 'c'
            ],
            [
                'teks_soal' => 'Pecahan yang senilai dengan 3/4 adalah...',
                'pilihan_a' => '6/8',
                'pilihan_b' => '9/16',
                'pilihan_c' => '5/8',
                'pilihan_d' => '12/15',
                'pilihan_e' => '4/5',
                'jawaban_benar' => 'a'
            ],
            [
                'teks_soal' => 'Hasil dari 2 1/2 + 1 1/4 adalah...',
                'pilihan_a' => '3 1/4',
                'pilihan_b' => '3 3/4',
                'pilihan_c' => '3 1/2',
                'pilihan_d' => '4',
                'pilihan_e' => '3 2/3',
                'jawaban_benar' => 'b'
            ],
            [
                'teks_soal' => 'KPK dari 12 dan 18 adalah...',
                'pilihan_a' => '24',
                'pilihan_b' => '36',
                'pilihan_c' => '48',
                'pilihan_d' => '6',
                'pilihan_e' => '72',
                'jawaban_benar' => 'b'
            ],
            [
                'teks_soal' => 'FPB dari 24 dan 36 adalah...',
                'pilihan_a' => '12',
                'pilihan_b' => '6',
                'pilihan_c' => '24',
                'pilihan_d' => '72',
                'pilihan_e' => '4',
                'jawaban_benar' => 'a'
            ],
            [
                'teks_soal' => 'Bentuk persen dari 0,45 adalah...',
                'pilihan_a' => '4,5%',
                'pilihan_b' => '45%',
                'pilihan_c' => '0,45%',
                'pilihan_d' => '450%',
                'pilihan_e' => '4500%',
                'jawaban_benar' => 'b'
            ],
            [
                'teks_soal' => 'Hasil dari 5² + 3³ adalah...',
                'pilihan_a' => '52',
                'pilihan_b' => '34',
                'pilihan_c' => '19',
                'pilihan_d' => '13',
                'pilihan_e' => '8',
                'jawaban_benar' => 'a'
            ],
            [
                'teks_soal' => 'Diketahui A = {x | 1 < x < 10, x bilangan ganjil}. Anggota himpunan A adalah...',
                'pilihan_a' => '{1, 3, 5, 7, 9}',
                'pilihan_b' => '{3, 5, 7}',
                'pilihan_c' => '{3, 5, 7, 9}',
                'pilihan_d' => '{1, 3, 5, 7}',
                'pilihan_e' => '{2, 4, 6, 8}',
                'jawaban_benar' => 'c'
            ],
            [
                'teks_soal' => 'Himpunan semesta yang mungkin dari {Ayam, Burung, Bebek} adalah...',
                'pilihan_a' => 'Hewan berkaki empat',
                'pilihan_b' => 'Hewan bertelur',
                'pilihan_c' => 'Tumbuhan',
                'pilihan_d' => 'Hewan melata',
                'pilihan_e' => 'Serangga',
                'jawaban_benar' => 'b'
            ],
            [
                'teks_soal' => 'Bentuk aljabar 3x - 5y + 7. Koefisien dari x adalah...',
                'pilihan_a' => '7',
                'pilihan_b' => '-5',
                'pilihan_c' => 'y',
                'pilihan_d' => '3',
                'pilihan_e' => '-3',
                'jawaban_benar' => 'd'
            ],
            [
                'teks_soal' => 'Hasil penjumlahan dari (2x + 3) dan (x - 5) adalah...',
                'pilihan_a' => '3x - 2',
                'pilihan_b' => '3x + 8',
                'pilihan_c' => 'x - 2',
                'pilihan_d' => 'x + 8',
                'pilihan_e' => '3x + 2',
                'jawaban_benar' => 'a'
            ],
            [
                'teks_soal' => 'Penyelesaian dari persamaan x + 5 = 12 adalah...',
                'pilihan_a' => '7',
                'pilihan_b' => '-7',
                'pilihan_c' => '17',
                'pilihan_d' => '-17',
                'pilihan_e' => '5',
                'jawaban_benar' => 'a'
            ],
            [
                'teks_soal' => 'Nilai x dari persamaan 2x - 4 = x + 6 adalah...',
                'pilihan_a' => '10',
                'pilihan_b' => '-10',
                'pilihan_c' => '2',
                'pilihan_d' => '-2',
                'pilihan_e' => '0',
                'jawaban_benar' => 'a'
            ],
            [
                'teks_soal' => 'Sebuah persegi panjang memiliki panjang (x+3) cm dan lebar x cm. Kelilingnya adalah...',
                'pilihan_a' => '(4x + 6) cm',
                'pilihan_b' => '(2x + 6) cm',
                'pilihan_c' => '(4x + 3) cm',
                'pilihan_d' => '(x² + 3x) cm',
                'pilihan_e' => '(2x + 3) cm',
                'jawaban_benar' => 'a'
            ],
            [
                'teks_soal' => 'Penyelesaian dari pertidaksamaan 2x > 8 adalah...',
                'pilihan_a' => 'x > 4',
                'pilihan_b' => 'x < 4',
                'pilihan_c' => 'x > -4',
                'pilihan_d' => 'x < -4',
                'pilihan_e' => 'x > 6',
                'jawaban_benar' => 'a'
            ],
            [
                'teks_soal' => 'Besar sudut siku-siku adalah...',
                'pilihan_a' => '30°',
                'pilihan_b' => '45°',
                'pilihan_c' => '60°',
                'pilihan_d' => '90°',
                'pilihan_e' => '180°',
                'jawaban_benar' => 'd'
            ],
            [
                'teks_soal' => 'Sudut yang besarnya lebih dari 90° dan kurang dari 180° disebut sudut...',
                'pilihan_a' => 'Lancip',
                'pilihan_b' => 'Tumpul',
                'pilihan_c' => 'Refleks',
                'pilihan_d' => 'Siku-siku',
                'pilihan_e' => 'Lurus',
                'jawaban_benar' => 'b'
            ],
            [
                'teks_soal' => 'Jika skala sebuah peta adalah 1 : 500.000 dan jarak pada peta 4 cm, jarak sebenarnya adalah...',
                'pilihan_a' => '2 km',
                'pilihan_b' => '20 km',
                'pilihan_c' => '200 km',
                'pilihan_d' => '2.000 km',
                'pilihan_e' => '0,2 km',
                'jawaban_benar' => 'b'
            ],
            [
                'teks_soal' => 'Perbandingan kelereng Andi dan Budi adalah 2 : 3. Jika jumlah kelereng mereka 50, maka banyak kelereng Andi adalah...',
                'pilihan_a' => '10',
                'pilihan_b' => '20',
                'pilihan_c' => '30',
                'pilihan_d' => '40',
                'pilihan_e' => '50',
                'jawaban_benar' => 'b'
            ]
        ];

        foreach ($soalData as $data) {
            Soal::create([
                'ujian_id' => $ujian->id,
                'teks_soal' => $data['teks_soal'],
                'pilihan_a' => $data['pilihan_a'],
                'pilihan_b' => $data['pilihan_b'],
                'pilihan_c' => $data['pilihan_c'],
                'pilihan_d' => $data['pilihan_d'],
                'pilihan_e' => $data['pilihan_e'],
                'jawaban_benar' => $data['jawaban_benar'],
            ]);
        }
    }
}
