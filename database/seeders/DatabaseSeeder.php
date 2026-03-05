<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            // MahasiswaSeeder::class,
            // DosenSeeder::class,
            // MatakuliahSeeder::class,
            // JadwalSeeder::class,
            // PesertaSeeder::class,
        ]);
    }
}
