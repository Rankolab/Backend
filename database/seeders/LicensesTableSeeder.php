<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\License;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LicensesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing plans to avoid duplicates
        DB::table("plans")->delete();
        
        // Create Plans
        $basicPlan = DB::table("plans")->insertGetId([
            "name" => "Basic Plan",
            "description" => "Entry level plan for small websites",
            "price" => 29.99,
            "interval" => "month", // Added interval
            "features" => json_encode(["Basic SEO", "Weekly Reports", "1 Website"]),
            "is_active" => true,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        $proPlan = DB::table("plans")->insertGetId([
            "name" => "Professional Plan",
            "description" => "Advanced features for growing businesses",
            "price" => 99.99,
            "interval" => "month", // Added interval
            "features" => json_encode(["Advanced SEO", "Daily Reports", "5 Websites", "Content Optimization"]),
            "is_active" => true,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        $enterprisePlan = DB::table("plans")->insertGetId([
            "name" => "Enterprise Plan",
            "description" => "Complete solution for large businesses",
            "price" => 299.99,
            "interval" => "month", // Added interval
            "features" => json_encode(["Premium SEO", "Real-time Reports", "Unlimited Websites", "Content Optimization", "API Access", "Dedicated Support"]),
            "is_active" => true,
            "created_at" => now(),
            "updated_at" => now(),
        ]);        // Get user IDs dynamically by email
        $johnDoeId = DB::table('users')->where('email', 'john@example.com')->value('id');
        $janeSmithId = DB::table('users')->where('email', 'jane@example.com')->value('id');
        $robertJohnsonId = DB::table('users')->where('email', 'robert@example.com')->value('id');
        
        // Create Licenses for users only if the users exist
        if ($johnDoeId) {
            License::create([
                'user_id' => $johnDoeId,
                'plan_id' => $basicPlan,
                'key' => 'LIC-' . strtoupper(substr(md5(uniqid()), 0, 16)),
                'type' => 'Basic', // Added type based on plan
                'status' => 'active',
                'expires_at' => Carbon::now()->addDays(30),
            ]);
        }

        if ($janeSmithId) {
            License::create([
                'user_id' => $janeSmithId,
                'plan_id' => $proPlan,
                'key' => 'LIC-' . strtoupper(substr(md5(uniqid()), 0, 16)),
                'type' => 'Professional', // Added type based on plan
                'status' => 'active',
                'expires_at' => Carbon::now()->addDays(30),
            ]);
        }

        if ($robertJohnsonId) {
            License::create([
                'user_id' => $robertJohnsonId,
                'plan_id' => $enterprisePlan,
                'key' => 'LIC-' . strtoupper(substr(md5(uniqid()), 0, 16)),
                'type' => 'Enterprise', // Added type based on plan
                'status' => 'active',
                'expires_at' => Carbon::now()->addDays(30),
            ]);
        }

        // Add an expired license for demonstration
        if ($johnDoeId) {
            License::create([
                'user_id' => $johnDoeId, // John Doe
                'plan_id' => $basicPlan,
                'key' => 'LIC-' . strtoupper(substr(md5(uniqid()), 0, 16)),
                'type' => 'Basic', // Added type based on plan
                'status' => 'expired',
                'expires_at' => Carbon::now()->subDays(15),
            ]);
        }
    }
}
