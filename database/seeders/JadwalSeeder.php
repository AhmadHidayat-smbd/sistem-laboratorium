<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Matakuliah;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel jadwal kosong unuk menghindari duplikasi jika di-seed ulang tanpa fresh
        // Jadwal::truncate(); // Uncomment jika ingin mengosongkan tabel terlebih dahulu

        $jadwalData = [
            [
                'kode_mk' => 'IF101', // Algoritma dan Pemrograman
                'hari' => 'Monday ',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
            ],
            [
                'kode_mk' => 'IF102', // Struktur Data
                'hari' => 'Tuesday',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '12:00:00',
            ],
            [
                'kode_mk' => 'IF201', // Basis Data
                'hari' => 'Wednesday',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '11:00:00',
            ],
            [
                'kode_mk' => 'IF202', // Pemrograman Web
                'hari' => 'Thursday',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '16:00:00',
            ],
            [
                'kode_mk' => 'IF301', // Kecerdasan Buatan
                'hari' => 'Friday',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '11:00:00',
            ],
        ];

        foreach ($jadwalData as $jadwal) {
            $mk = Matakuliah::where('kode', $jadwal['kode_mk'])->first();

            if ($mk) {
                Jadwal::create([
                    'matakuliah_id' => $mk->id,
                    'hari' => $jadwal['hari'],
                    'jam_mulai' => $jadwal['jam_mulai'],
                    'jam_selesai' => $jadwal['jam_selesai'],
                ]);
            }
        }
    }
}
