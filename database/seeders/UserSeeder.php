<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@usahaku.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@usahaku.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'phone' => '081234567891',
        ]);
    }
}