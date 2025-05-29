<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'superadmin@example.com'],
            [
                'name'         => 'Super Admin',
                'email'        => 'superadmin@example.com',
                'password'     => Hash::make('supersecurepassword'),
                'role'         => 'super_admin',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ]
        );
    }
}
