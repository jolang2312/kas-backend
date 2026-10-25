<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // User Admin
        User::updateOrCreate(
            ['email' => 'admin@kas.com'],
            [
                'name' => 'Administrator Kas',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // User Anggota Default
        User::updateOrCreate(
            ['email' => 'anggota@kas.com'],
            [
                'name' => 'Anggota Kas',
                'password' => Hash::make('user123'),
                'role' => 'anggota',
            ]
        );
    }
}