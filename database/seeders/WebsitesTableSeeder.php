<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Website;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WebsitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get user IDs dynamically by email
        $johnDoeId = DB::table('users')->where('email', 'john@example.com')->value('id');
        $janeSmithId = DB::table('users')->where('email', 'jane@example.com')->value('id');
        $robertJohnsonId = DB::table('users')->where('email', 'robert@example.com')->value('id');
        
        // Create websites for John Doe
        if ($johnDoeId) {
            Website::create([
                'user_id' => $johnDoeId,
                'name' => 'Tech Blog',
                'url' => 'https://techblog.example.com',
                'domain' => 'techblog.example.com',
                'niche' => 'Technology',
                'website_type' => 'existing',
                'description' => 'A blog about the latest technology trends and reviews',
                'status' => 'active',
                'settings' => json_encode([
                    'seo_enabled' => true,
                    'analytics_enabled' => true,
                    'social_media_enabled' => true
                ]),
            ]);
        }

        // Create websites for Jane Smith
        if ($janeSmithId) {
            Website::create([
                'user_id' => $janeSmithId,
                'name' => 'Fashion Store',
                'url' => 'https://fashionstore.example.com',
                'domain' => 'fashionstore.example.com',
                'niche' => 'Fashion',
                'website_type' => 'existing',
                'description' => 'Online fashion store with the latest trends',
                'status' => 'active',
                'settings' => json_encode([
                    'seo_enabled' => true,
                    'analytics_enabled' => true,
                    'social_media_enabled' => true,
                    'ecommerce_enabled' => true
                ]),
            ]);

            
            Website::create([
                'user_id' => $janeSmithId,
                'name' => 'Beauty Blog',
                'url' => 'https://beautyblog.example.com',
                'domain' => 'beautyblog.example.com',
                'niche' => 'Beauty',
                'website_type' => 'existing',
                'description' => 'Tips and tricks for beauty enthusiasts',
                'status' => 'active',
                'settings' => json_encode([
                    'seo_enabled' => true,
                    'analytics_enabled' => true,
                    'social_media_enabled' => true
                ]),
            ]);
        }

        // Create websites for Robert Johnson
        if ($robertJohnsonId) {
            Website::create([
                'user_id' => $robertJohnsonId,
                'name' => 'Business Consulting',
                'url' => 'https://consulting.example.com',
                'domain' => 'consulting.example.com',
                'niche' => 'Business',
                'website_type' => 'existing',
                'description' => 'Professional business consulting services',
                'status' => 'active',
                'settings' => json_encode([
                    'seo_enabled' => true,
                    'analytics_enabled' => true,
                    'social_media_enabled' => true,
                    'appointment_booking' => true
                ]),
            ]);

            Website::create([
                'user_id' => $robertJohnsonId,
                'name' => 'Investment Portfolio',
                'url' => 'https://investments.example.com',
                'domain' => 'investments.example.com',
                'niche' => 'Finance',
                'website_type' => 'existing',
                'description' => 'Investment strategies and portfolio management',
                'status' => 'active',
                'settings' => json_encode([
                    'seo_enabled' => true,
                    'analytics_enabled' => true,
                    'members_area' => true
                ]),
            ]);
        }
    }
}
