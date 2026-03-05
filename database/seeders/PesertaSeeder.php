<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use Illuminate\Database\Seeder;

class PesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     $students = Mahasiswa::all();
    //     $courses = Matakuliah::all();

    //     if ($students->isEmpty() || $courses->isEmpty()) {
    //         echo "Mahasiswa atau Matakuliah kosong. Pastikan seeder lain sudah dijalankan.\n";
    //         return;
    //     }

    //     // CARA 2: MANUAL (SPESIFIK) - DIAKTIFKAN
    //     // Contoh: Budi ambil Algoritma & Struktur Data
        
    //     $budi = Mahasiswa::where('nim', '20115001')->first();
    //     $algo = Matakuliah::where('kode', 'IF101')->first();
    //     $strukdat = Matakuliah::where('kode', 'IF102')->first();
        
    //     if ($budi && $algo && $strukdat) {
    //         $budi->matakuliah()->syncWithoutDetaching([$algo->id, $strukdat->id]);
    //     }
        
    //     // Contoh: Siti ambil Basis Data
    //     $siti = Mahasiswa::where('nim', '20115002')->first();
    //     $basdat = Matakuliah::where('kode', 'IF201')->first();
        
    //     if ($siti && $basdat) {
    //         $siti->matakuliah()->syncWithoutDetaching([$basdat->id]);
    //     }
    // }
}
