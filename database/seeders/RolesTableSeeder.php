<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('roles')->insert([
            ['id' => 1, 'role_name' => 'admin', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'role_name' => 'operator', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'role_name' => 'superadmin', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}


