<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Blog;
use App\Models\User;
use App\Models\BlogCategory; // Import BlogCategory
use League\CommonMark\CommonMarkConverter;
use Carbon\Carbon; // Import Carbon for dates

class SeedBlogsFromMarkdown extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-blogs-from-markdown {--author-id=1 : The ID of the user to assign as author} {--status=published : The status to assign to the blog posts} {--category-slug=general : The slug of the category to assign}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed blog posts from Markdown files stored in storage/app/blog';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting blog seeding process from Markdown files...');

        $authorId = $this->option('author-id');
        $status = $this->option('status');
        $categorySlug = $this->option('category-slug');

        // Validate author exists
        $author = User::find($authorId);
        if (!$author) {
            $this->error("Author with ID {$authorId} not found. Attempting fallback...");
            $author = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['admin', 'superadmin']);
            })->first();
            if (!$author) {
                $this->error('No admin user found as fallback. Cannot proceed.');
                return 1;
            }
            $this->warn("Using author ID {$author->id} ({$author->name}) as fallback.");
            $authorId = $author->id;
        }

        // Validate category exists
        $category = BlogCategory::where('slug', $categorySlug)->first();
        if (!$category) {
            $this->error("Category with slug '{$categorySlug}' not found. Ensure it exists or run the BlogCategoriesSeeder.");
            // Attempt fallback to the first category found
            $category = BlogCategory::first();
            if (!$category) {
                $this->error('No categories found at all. Cannot proceed.');
                return 1;
            }
            $this->warn("Using category ID {$category->id} ('{$category->name}') as fallback.");
        }
        $categoryId = $category->id;

        $markdownFiles = File::glob(storage_path('app/blog/*.md'));
        $count = 0;

        if (empty($markdownFiles)) {
            $this->warn('No Markdown files found in storage/app/blog.');
            return 0;
        }

        $converter = new CommonMarkConverter();

        foreach ($markdownFiles as $filePath) {
            $filename = basename($filePath);
            // Skip README or other non-blog files
            if (in_array(strtolower($filename), ['readme.md', 'backend_analysis.md', 'deployment_instructions.md', 'development_plan.md', 'implementation_plan.md', 'plugin_analysis.md', 'project_completion_status.md', 'website_requirements.md', 'website_structure.md'])) {
                $this->line("Skipping non-blog file: {$filename}");
                continue;
            }

            $markdownContent = File::get($filePath);

            // Extract title
            $title = Str::headline(str_replace(['-', '_'], ' ', pathinfo($filename, PATHINFO_FILENAME)));
            if (preg_match('/^#\s+(.*)/m', $markdownContent, $matches)) {
                $title = trim($matches[1]);
            }

            // Generate slug
            $slug = Str::slug($title);

            // Check if slug exists
            if (Blog::where('slug', $slug)->exists()) {
                $this->warn("Blog post with slug '{$slug}' already exists. Skipping file: {$filename}");
                continue;
            }

            try {
                Blog::create([
                    'title' => $title,
                    'slug' => $slug,
                    'content' => $markdownContent, // Use 'content' field
                    'excerpt' => Str::limit(strip_tags($markdownContent), 150),
                    'author_id' => $authorId,
                    'category_id' => $categoryId, // Assign category ID
                    'status' => $status,
                    'featured_image' => null, // Set featured_image (nullable)
                    'published_at' => ($status === 'published') ? Carbon::now() : null, // Set published_at if status is published
                    'is_featured' => false, // Default is_featured
                ]);
                $this->info("Successfully seeded blog post: {$title} (from {$filename})");
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to seed blog post from {$filename}: " . $e->getMessage());
            }
        }

        $this->info("Finished seeding process. {$count} blog posts were seeded.");
        return 0;
    }
}

