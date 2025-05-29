<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AiTool;
use App\Models\AiToolCategory;

class AiToolsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create AI Tool Categories
        $contentCategory = AiToolCategory::create([
            'name' => 'Content Generation',
            'slug' => 'content-generation',
            'description' => 'Tools for generating and optimizing content'
        ]);

        $seoCategory = AiToolCategory::create([
            'name' => 'SEO Tools',
            'slug' => 'seo-tools',
            'description' => 'Tools for search engine optimization'
        ]);

        $analyticsCategory = AiToolCategory::create([
            'name' => 'Analytics',
            'slug' => 'analytics',
            'description' => 'Tools for data analysis and reporting'
        ]);

        // Create AI Tools
        AiTool::create([
            'name' => 'RankoWriter',
            'slug' => 'ranko-writer',
            'description' => 'AI-powered content generation tool for SEO-optimized articles and blog posts',
            'url' => 'https://tools.rankolab.com/writer',
            'category_id' => $contentCategory->id,
            'is_featured' => true,
            'is_premium' => false,
            'is_active' => true,
            'features' => json_encode([
                'SEO-optimized content generation',
                'Multiple content formats',
                'Keyword optimization',
                'Readability analysis'
            ]),
        ]);

        AiTool::create([
            'name' => 'RankoAnalyzer',
            'slug' => 'ranko-analyzer',
            'description' => 'Comprehensive SEO analysis tool for websites and content',
            'url' => 'https://tools.rankolab.com/analyzer',
            'category_id' => $seoCategory->id,
            'is_featured' => true,
            'is_premium' => true,
            'is_active' => true,
            'features' => json_encode([
                'On-page SEO analysis',
                'Competitor analysis',
                'Keyword gap analysis',
                'Backlink profile analysis',
                'Technical SEO audit'
            ]),
        ]);

        AiTool::create([
            'name' => 'RankoMetrics',
            'slug' => 'ranko-metrics',
            'description' => 'Advanced analytics dashboard for tracking SEO performance',
            'url' => 'https://tools.rankolab.com/metrics',
            'category_id' => $analyticsCategory->id,
            'is_featured' => false,
            'is_premium' => true,
            'is_active' => true,
            'features' => json_encode([
                'Real-time performance tracking',
                'Custom reporting',
                'Keyword position tracking',
                'Traffic analysis',
                'Conversion tracking'
            ]),
        ]);

        AiTool::create([
            'name' => 'RankoKeywords',
            'slug' => 'ranko-keywords',
            'description' => 'AI-powered keyword research and analysis tool',
            'url' => 'https://tools.rankolab.com/keywords',
            'category_id' => $seoCategory->id,
            'is_featured' => true,
            'is_premium' => false,
            'is_active' => true,
            'features' => json_encode([
                'Keyword discovery',
                'Search volume analysis',
                'Keyword difficulty scoring',
                'Trend analysis',
                'Competitor keyword analysis'
            ]),
        ]);

        AiTool::create([
            'name' => 'RankoInsights',
            'slug' => 'ranko-insights',
            'description' => 'AI-driven content performance insights and recommendations',
            'url' => 'https://tools.rankolab.com/insights',
            'category_id' => $analyticsCategory->id,
            'is_featured' => false,
            'is_premium' => false,
            'is_active' => true,
            'features' => json_encode([
                'Content performance analysis',
                'Engagement metrics',
                'Improvement recommendations',
                'Audience insights',
                'Content gap analysis'
            ]),
        ]);
    }
}
