<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dosen::create([
            'nama' => 'Dr. Ahmad Fauzi, M.Kom',
            'email' => 'ahmad.fauzi@itlabs.ac.id',
            'password' => Hash::make('password'),
            'rfid_uid' => '7777777777',
        ]);

        Dosen::create([
            'nama' => 'Sri Rahayu, S.Kom, M.Eng',
            'email' => 'Rahayu@gmail.com',
            'password' => Hash::make('password'),
            'rfid_uid' => '99999999999',
        ]);

        Dosen::create([
            'nama' => 'Budi Santoso, S.Kom, M.Sc',
            'email' => 'budi.santoso@itlabs.ac.id',
            'password' => Hash::make('password'),
            'rfid_uid' => 4444444444,
        ]);
    }
}
