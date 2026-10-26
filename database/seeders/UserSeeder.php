<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@kas.com'],
            [
                'name' => 'Administrator Kas',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Akun Anggota
        User::updateOrCreate(
            ['email' => 'anggota@kas.com'],
            [
                'name' => 'Anggota Kas',
                'password' => Hash::make('password123'),
                'role' => 'anggota',
            ]
        );
    }
}