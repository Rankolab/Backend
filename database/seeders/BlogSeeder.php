<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Blog;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Create Rankolab user if not exists
        $user = User::firstOrCreate(
            ['email' => 'admin@rankolab.com'],
            ['name' => 'Rankolab', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        // Load blog data
        $json = file_get_contents(database_path('seeders/data/seed_blogs.json'));
        $blogs = json_decode($json, true);

        foreach ($blogs as $data) {
            Blog::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'excerpt' => $data['excerpt'],
                'body' => $data['body'],
                'status' => $data['status'],
                'published_at' => $data['published_at'],
                'author_id' => $user->id,
            ]);
        }
    }
}
