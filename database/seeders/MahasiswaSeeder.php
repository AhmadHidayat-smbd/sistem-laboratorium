<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswa = [
            [
                'nama' => 'Ahmad Nur Hidayah',
                'nim' => '22330031',
                'rfid_uid' => '0000428960',
                'password' => '22330031',
            ],
            [
                'nama' => 'Muhammad Fuad',
                'nim' => '22330024',
                'rfid_uid' => '0014491100',
                 'password' => '22330024',
            ],
            [
                'nama' => 'Faisal Maulana',
                'nim' => '22330030',
                'rfid_uid' => '0015626574',
                 'password' => '22330030',
            ],
            [
                'nama' => 'Alvian Damarjati',
                'nim' => '22330032',
                'rfid_uid' => '0013254236',
                 'password' => '22330032',
            ],
            [
                'nama' => 'Tio Febrian',
                'nim' => '22330029',
                'rfid_uid' => '0015626579',
                 'password' => '22330029',
            ],
            [
                'nama' => 'Fauzul Wastha',
                'nim' => '22330007',
                'rfid_uid' => '0015626682',
                 'password' => '22330007',
            ],
            [
                'nama' => 'Mahendra Anggitya',
                'nim' => '22330034',
                'rfid_uid' => '0000676882',
                 'password' => '22330034',
            ],
            [
                'nama' => 'Calvin Josua',
                'nim' => '22330002',
                'rfid_uid' => '0014494488',
                 'password' => '22330002',
            ],
            [
                'nama' => 'Maria Sintia',
                'nim' => '22330033',
                'rfid_uid' => '0013213223',
                 'password' => '22330033',
            ],
            [
                'nama' => 'Rizky Fadhilah',
                'nim' => '22330028',
                'rfid_uid' => '0015626690',
                 'password' => '22330028',
            ],
        ];

        foreach ($mahasiswa as $mhs) {
            $mhs['password'] = Hash::make($mhs['nim']); // Password set to NIM
            Mahasiswa::updateOrCreate(['nim' => $mhs['nim']], $mhs);
        }
    }
}
