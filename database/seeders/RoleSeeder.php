<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['superadmin', 'admin', 'editor', 'support'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
