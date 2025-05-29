<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BlogCategory;

class BlogCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default category if it doesn't exist
        BlogCategory::firstOrCreate(
            ["slug" => "general"], // Use slug to check for existence
            [
                "name" => "General",
                "slug" => "general",
                "description" => "Default category for blog posts."
            ]
        );

        $this->command->info("Default blog category seeded.");
    }
}

