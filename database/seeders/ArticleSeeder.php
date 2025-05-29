<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $articlePath = storage_path('app/blog'); // You'll move the extracted files here

        if (!File::exists($articlePath)) {
            $this->command->error("❌ Directory not found: $articlePath");
            return;
        }

        $files = File::files($articlePath);

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $slug = Str::slug(str_replace('.md', '', $filename));
            $title = ucwords(str_replace('-', ' ', str_replace('.md', '', $filename)));
            $content = File::get($file->getRealPath());

            Article::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'content' => $content,
                    'author' => 'Admin',
                    'status' => 'published',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info("✅ " . count($files) . " articles seeded successfully.");
    }
}
