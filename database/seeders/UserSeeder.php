<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@cer.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_admin' => '1',
                'created_at' => now()
            ],
            [
                'name' => 'user',
                'email' => 'user@cer.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_admin' => '0',
                'created_at' => now()
            ],
        ];

        DB::table('users')->insert($users);
    }
}
