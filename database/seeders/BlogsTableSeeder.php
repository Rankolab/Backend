<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\DB;

class BlogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing blog categories and blogs to avoid duplicates
        DB::table('blogs')->delete();
        DB::table('blog_categories')->delete();
        
        // Reset auto-increment counters
        DB::statement('UPDATE sqlite_sequence SET seq = 0 WHERE name = "blog_categories"');
        DB::statement('UPDATE sqlite_sequence SET seq = 0 WHERE name = "blogs"');
        
        // Create Blog Categories
        $seoCategory = BlogCategory::create([
            'name' => 'SEO',
            'slug' => 'seo',
            'description' => 'Search Engine Optimization tips and strategies'
        ]);

        $contentCategory = BlogCategory::create([
            'name' => 'Content Marketing',
            'slug' => 'content-marketing',
            'description' => 'Content creation and marketing strategies'
        ]);

        $techCategory = BlogCategory::create([
            'name' => 'Technical SEO',
            'slug' => 'technical-seo',
            'description' => 'Technical aspects of search engine optimization'
        ]);

        // Create Blog Posts
        // First check if the file exists before trying to read it
        $contentPath = storage_path('app/blog/ultimate-guide-to-keyword-research.md');
        $content = file_exists($contentPath) ? file_get_contents($contentPath) : 'Sample content for keyword research guide';
        
        Blog::create([
            'title' => 'Ultimate Guide to Keyword Research in 2025',
            'slug' => 'ultimate-guide-to-keyword-research-2025',
            'excerpt' => 'Learn the most effective keyword research strategies for 2025 to boost your website rankings.',
            'content' => $content,
            'featured_image' => 'images/blog/keyword-research.jpg',
            'status' => 'published',
            'category_id' => $seoCategory->id,
            'author_id' => 1, // Super Admin
            'published_at' => now()->subDays(5),
            'is_featured' => true
        ]);

        $contentPath = storage_path('app/blog/content-optimization-strategies.md');
        $content = file_exists($contentPath) ? file_get_contents($contentPath) : 'Sample content for content optimization strategies';
        
        Blog::create([
            'title' => 'Content Optimization Strategies That Work',
            'slug' => 'content-optimization-strategies',
            'excerpt' => 'Discover proven content optimization techniques to improve engagement and conversions.',
            'content' => $content,
            'featured_image' => 'images/blog/content-optimization.jpg',
            'status' => 'published',
            'category_id' => $contentCategory->id,
            'author_id' => 2, // Admin User
            'published_at' => now()->subDays(10),
            'is_featured' => false
        ]);

        $contentPath = storage_path('app/blog/technical-seo-guide-2025.md');
        $content = file_exists($contentPath) ? file_get_contents($contentPath) : 'Sample content for technical SEO guide';
        
        Blog::create([
            'title' => 'Technical SEO Guide for 2025',
            'slug' => 'technical-seo-guide-2025',
            'excerpt' => 'Master the technical aspects of SEO with this comprehensive guide for 2025.',
            'content' => $content,
            'featured_image' => 'images/blog/technical-seo.jpg',
            'status' => 'published',
            'category_id' => $techCategory->id,
            'author_id' => 1, // Super Admin
            'published_at' => now()->subDays(15),
            'is_featured' => true
        ]);

        $contentPath = storage_path('app/blog/ai-in-seo.md');
        $content = file_exists($contentPath) ? file_get_contents($contentPath) : 'Sample content for AI in SEO';
        
        Blog::create([
            'title' => 'How AI is Transforming SEO in 2025',
            'slug' => 'ai-in-seo',
            'excerpt' => 'Explore how artificial intelligence is revolutionizing search engine optimization strategies.',
            'content' => $content,
            'featured_image' => 'images/blog/ai-seo.jpg',
            'status' => 'published',
            'category_id' => $seoCategory->id,
            'author_id' => 2, // Admin User
            'published_at' => now()->subDays(20),
            'is_featured' => false
        ]);

        $contentPath = storage_path('app/blog/mobile-seo-guide-2025.md');
        $content = file_exists($contentPath) ? file_get_contents($contentPath) : 'Sample content for mobile SEO guide';
        
        Blog::create([
            'title' => 'Mobile SEO Best Practices for 2025',
            'slug' => 'mobile-seo-guide-2025',
            'excerpt' => 'Learn how to optimize your website for mobile search in 2025.',
            'content' => $content,
            'featured_image' => 'images/blog/mobile-seo.jpg',
            'status' => 'published',
            'category_id' => $techCategory->id,
            'author_id' => 1, // Super Admin
            'published_at' => now()->subDays(25),
            'is_featured' => false
        ]);
    }
}
