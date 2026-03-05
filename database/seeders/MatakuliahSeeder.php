<?php

namespace Database\Seeders;

use App\Models\Matakuliah;
use Illuminate\Database\Seeder;

class MatakuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $matakuliah = [
            [
                'kode' => 'IF101',
                'nama' => 'Algoritma dan Pemrograman',
                'tahun_ajaran' => '2025-1',
            ],
            [
                'kode' => 'IF102',
                'nama' => 'Struktur Data',
                'tahun_ajaran' => '2025-1',
            ],
            [
                'kode' => 'IF201',
                'nama' => 'Basis Data',
                'tahun_ajaran' => '2025-1',
            ],
            [
                'kode' => 'IF202',
                'nama' => 'Pemrograman Web',
                'tahun_ajaran' => '2025-2',
            ],
            [
                'kode' => 'IF301',
                'nama' => 'Kecerdasan Buatan',
                'tahun_ajaran' => '2025-2',
            ],
        ];

        foreach ($matakuliah as $mk) {
            Matakuliah::create($mk);
        }
    }
}
