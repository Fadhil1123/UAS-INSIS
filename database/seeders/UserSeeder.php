<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'nomor_induk' => 'ADMIN001',
            'password' => '12345678',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Dosen Demo',
            'nomor_induk' => '198709102020121001',
            'password' => '12345678',
            'role' => 'dosen',
        ]);

        User::create([
            'name' => 'Mahasiswa Demo',
            'nomor_induk' => '2310817210001',
            'password' => '12345678',
            'role' => 'mahasiswa',
        ]);
    }
}