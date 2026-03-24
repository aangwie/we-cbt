<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaTemplateExport implements WithHeadings
{
    public function headings(): array
    {
        return [
            'nama',
            'nisn',
            'kelas',
            'jenis_kelamin',
            'tanggal_lahir',
        ];
    }
}
