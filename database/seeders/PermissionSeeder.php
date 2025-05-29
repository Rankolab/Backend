<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define core permissions
        $permissions = [
            // User Management
            ["name" => "view_users", "description" => "View user list and details"],
            ["name" => "create_users", "description" => "Create new users"],
            ["name" => "edit_users", "description" => "Edit existing users"],
            ["name" => "delete_users", "description" => "Delete users"],
            ["name" => "manage_user_roles", "description" => "Assign roles to users"],

            // Role & Permission Management
            ["name" => "view_roles", "description" => "View roles and their permissions"],
            ["name" => "create_roles", "description" => "Create new roles"],
            ["name" => "edit_roles", "description" => "Edit existing roles and assign permissions"],
            ["name" => "delete_roles", "description" => "Delete roles"],
            ["name" => "view_permissions", "description" => "View available permissions"],
            ["name" => "create_permissions", "description" => "Create new permissions"],
            ["name" => "delete_permissions", "description" => "Delete permissions"],

            // Website Management
            ["name" => "view_websites", "description" => "View website list and details"],
            ["name" => "create_websites", "description" => "Add new websites"],
            ["name" => "edit_websites", "description" => "Edit existing websites"],
            ["name" => "delete_websites", "description" => "Delete websites"],

            // Content Management (Blogs/Articles)
            ["name" => "view_content", "description" => "View blog posts/articles"],
            ["name" => "create_content", "description" => "Create new blog posts/articles"],
            ["name" => "edit_content", "description" => "Edit existing blog posts/articles"],
            ["name" => "publish_content", "description" => "Publish or unpublish content"],
            ["name" => "delete_content", "description" => "Delete blog posts/articles"],

            // License Management
            ["name" => "view_licenses", "description" => "View license list and details"],
            ["name" => "create_licenses", "description" => "Manually create licenses"],
            ["name" => "edit_licenses", "description" => "Edit existing licenses"],
            ["name" => "delete_licenses", "description" => "Delete licenses"],

            // AI Tools Management
            ["name" => "view_aitools", "description" => "View AI tools list"],
            ["name" => "create_aitools", "description" => "Create new AI tools"],
            ["name" => "edit_aitools", "description" => "Edit existing AI tools"],
            ["name" => "delete_aitools", "description" => "Delete AI tools"],

            // Settings & Configuration
            ["name" => "manage_settings", "description" => "Manage application settings"],
            ["name" => "manage_api_keys", "description" => "Manage external API keys"],

            // Analytics & Reporting
            ["name" => "view_analytics", "description" => "View system analytics and reports"],

            // Payouts & Affiliates
            ["name" => "manage_payouts", "description" => "Manage affiliate payouts"],
            ["name" => "manage_affiliates", "description" => "Manage affiliate program settings and users"],

            // Super Admin / AI Agent
            ["name" => "access_superadmin_features", "description" => "Access super admin specific features"],
            ["name" => "manage_ai_agent", "description" => "Manage AI agent settings and operations"],
        ];

        Log::info("Seeding permissions...");
        DB::beginTransaction();
        try {
            foreach ($permissions as $permissionData) {
                Permission::updateOrCreate(
                    ["name" => $permissionData["name"]], // Check based on name
                    $permissionData // Data to insert or update
                );
                Log::info("Permission seeded: " . $permissionData["name"]);
            }

            // --- Assign Permissions to Core Roles ---
            $adminRole = Role::firstOrCreate(["name" => "admin"]);
            $superAdminRole = Role::firstOrCreate(["name" => "superadmin"]);
            $userRole = Role::firstOrCreate(["name" => "user"]); // Basic user role

            // Super Admin gets all permissions
            $allPermissionIds = Permission::pluck("id");
            $superAdminRole->permissions()->sync($allPermissionIds);
            Log::info("Synced all permissions for superadmin role.");

            // Admin gets most permissions (excluding superadmin specific ones)
            $adminPermissions = Permission::where("name", "!= ", "access_superadmin_features")
                                        ->where("name", "!= ", "manage_ai_agent")
                                        ->pluck("id");
            $adminRole->permissions()->sync($adminPermissions);
            Log::info("Synced relevant permissions for admin role.");

            // Basic User role gets minimal permissions (adjust as needed)
            $userPermissions = Permission::whereIn("name", [
                // Add permissions basic users should have, e.g.:
                // "view_own_profile",
                // "edit_own_profile",
                // "view_own_websites",
                // "manage_own_content",
            ])->pluck("id");
            $userRole->permissions()->sync($userPermissions);
            Log::info("Synced basic permissions for user role.");

            DB::commit();
            Log::info("Permissions seeding completed successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error seeding permissions: " . $e->getMessage());
            $this->command->error("Failed to seed permissions: " . $e->getMessage());
        }
    }
}

