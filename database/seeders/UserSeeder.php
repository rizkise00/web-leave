<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'           => 'Manajer',
            'email'          => 'manajer@webcuti.com',
            'password'       => Hash::make('password'),
            'role'           => 'manajer',
            'account_status' => 'approved',
        ]);

        User::create([
            'name'           => 'User',
            'email'          => 'user@webcuti.com',
            'password'       => Hash::make('password'),
            'role'           => 'user',
            'account_status' => 'approved',
        ]);
    }
}
