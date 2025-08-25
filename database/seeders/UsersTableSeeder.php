<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('users')->insert([
            [
                'id' => 1,
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'name' => 'Administrator',
                'role_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'username' => 'operator',
                'password' => Hash::make('operator123'),
                'name' => 'Operator User',
                'role_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'username' => 'superadmin',
                'password' => Hash::make('superadmin123'),
                'name' => 'Super Admin User',
                'role_id' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}


