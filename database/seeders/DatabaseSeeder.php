<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call your seeders here
        $this->call([
            UsersTableSeeder::class,
            WebsitesTableSeeder::class, // Seed websites before licenses
            LicensesTableSeeder::class, // Now seed licenses, which might reference websites and plans (plans are seeded within LicensesTableSeeder)
            // Temporarily commenting out problematic seeders for SQLite integration
            // BlogsTableSeeder::class,
            // AiToolsTableSeeder::class,
            // Keep existing seeders if they're still needed
            // SuperAdminSeeder::class,
            // ArticleSeeder::class,
        ]);
    }
}
