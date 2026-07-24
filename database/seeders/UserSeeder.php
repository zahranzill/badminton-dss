<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun pelatih/analis default jika belum ada
        User::updateOrCreate(
            ['email' => 'pelatih@dss.com'],
            [
                'name' => 'Coach Herry IP',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
