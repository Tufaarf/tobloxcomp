<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@rtravel.com'], // cek email unik
            [
                'name' => 'Administrator',
                'password' => Hash::make('admintravel135'), // ganti dengan password aman
            ]
        );
    }
}
