<?php

namespace App\Imports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class PembayaranImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $nim = $row['nim'];
            $nama = $row['nama'];

            // Loop through the rest of the columns as year semesters
            foreach ($row as $key => $value) {
                if (in_array($key, ['nim', 'nama'])) {
                    continue;
                }

                // Only skip if the cell is truly empty (null or empty string)
                if ($value === null || $value === '') {
                    continue;
                }

                // Clean nominal value if it's a string like "Rp. 550.000"
                $nominal = $value;
                if (is_string($value)) {
                    $nominal = str_replace(['Rp', '.', ',', ' '], '', $value);
                }

                // Process if it's a numeric value (including 0 for KIP)
                if (is_numeric($nominal)) {
                    // Normalize year semester name (e.g., 22_1 to 2022-1)
                    $tahun_ajaran = $this->normalizeTahunAjaran($key);

                    // Create or update? User said "masukan data", usually we avoid duplicates for same NIM & Semester
                    Pembayaran::updateOrCreate(
                        [
                            'nim' => $nim,
                            'tahun_ajaran' => $tahun_ajaran,
                        ],
                        [
                            'nama' => $nama,
                            'nominal' => $nominal,
                            'tanggal_pembayaran' => Carbon::now(), // Use today or a specific logic
                        ]
                    );
                }
            }
        }
    }

    private function normalizeTahunAjaran($key)
    {
        // Convert something like 22_1 to 2022-1, or leave as is if already correct
        if (preg_match('/^(\d{2})_(\d)$/', $key, $matches)) {
            return "20" . $matches[1] . "-" . $matches[2];
        }
        
        // Replace underscore with dash for simplicity (22_1 -> 22-1)
        return str_replace('_', '-', $key);
    }
}
