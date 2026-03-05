<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Cek apakah admin sudah ada
        $adminExists = User::where('email', 'admin@example.com')->first();

        if (!$adminExists) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'asisten_labkom@janabadra.ac.id',
                'password' => Hash::make('aslabasoy22'),
                'role' => 'admin',
            ]);
        } else {
            echo "Admin sudah ada!\n";
        }
    }
}
