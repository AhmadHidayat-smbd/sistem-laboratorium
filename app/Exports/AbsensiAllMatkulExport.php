<?php

namespace App\Exports;

use App\Models\Matakuliah;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AbsensiAllMatkulExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        $matakuliah = Matakuliah::orderBy('nama')->get();

        foreach ($matakuliah as $mk) {
            $sheets[] = new AbsensiPerMatkulSheet($mk);
        }

        return $sheets;
    }
}
