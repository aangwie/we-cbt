<?php

namespace App\Imports;

use App\Models\Soal;
use App\Models\PaketSoal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class SoalImport implements ToCollection, WithHeadingRow
{
    private $paketSoal;
    public $successCount = 0;
    public $failedCount = 0;
    public $errors = [];

    public function __construct(PaketSoal $paketSoal)
    {
        $this->paketSoal = $paketSoal;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $item) {
            $rowNumber = $index + 2; // +1 for 0-index, +1 for heading row
            $row = is_array($item) ? $item : (method_exists($item, 'toArray') ? $item->toArray() : (array)$item);

            // Periksa jika baris kosong sepenuhnya (skip baris kosong)
            if (!isset($row['teks_soal']) || empty(trim($row['teks_soal']))) {
                continue;
            }

            $validator = Validator::make($row, [
                'teks_soal' => 'required',
                'pilihan_a' => 'required',
                'pilihan_b' => 'required',
                'pilihan_c' => 'required',
                'pilihan_d' => 'required',
                'pilihan_e' => 'required',
                'jawaban_benar' => 'required|in:A,B,C,D,E,a,b,c,d,e',
            ]);

            if ($validator->fails()) {
                $this->failedCount++;
                $this->errors[] = "Baris $rowNumber: " . implode(', ', $validator->errors()->all());
                continue;
            }

            try {
                Soal::create([
                    'paket_soal_id' => $this->paketSoal->id,
                    'mapel_id' => $this->paketSoal->mapel_id,
                    'kelas' => $this->paketSoal->kelas,
                    'teks_soal' => $row['teks_soal'],
                    'pilihan_a' => $row['pilihan_a'],
                    'pilihan_b' => $row['pilihan_b'],
                    'pilihan_c' => $row['pilihan_c'],
                    'pilihan_d' => $row['pilihan_d'],
                    'pilihan_e' => $row['pilihan_e'],
                    'jawaban_benar' => strtolower($row['jawaban_benar']),
                ]);
                $this->successCount++;
            } catch (\Exception $e) {
                $this->failedCount++;
                $this->errors[] = "Baris $rowNumber: " . $e->getMessage();
            }
        }
    }
}
