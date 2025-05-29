<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks for SQLite
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        // Truncate the users table first to avoid duplicate entries
        // Use truncate which might be safer or handle constraints differently in some drivers
        // However, raw delete is explicit. Let's stick with delete but ensure FKs are off.
        DB::table('users')->delete(); // Use Query Builder delete which might be slightly safer than raw statement
        
        // Re-enable foreign key checks for SQLite after seeding if they were disabled
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }
        
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@rankolab.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@rankolab.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Regular Users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Robert Johnson',
            'email' => 'robert@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }
}
