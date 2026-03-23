<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $tanggalLahir = null;
        if (isset($row['tanggal_lahir'])) {
            if (is_numeric($row['tanggal_lahir'])) {
                $tanggalLahir = Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d');
            } else {
                $tanggalLahir = date('Y-m-d', strtotime($row['tanggal_lahir']));
            }
        }

        return new Siswa([
            'name' => $row['nama'],
            'nisn' => $row['nisn'],
            'kelas' => $row['kelas'],
            'jenis_kelamin' => strtoupper($row['jenis_kelamin']),
            'tanggal_lahir' => $tanggalLahir,
        ]);
    }
}
