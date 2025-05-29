<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("settings", function (Blueprint $table) {
            $table->id();
            $table->string("key")->unique(); // e.g., "site_name", "admin_email", "pagination_limit"
            $table->text("value")->nullable();
            $table->string("type")->default("string"); // e.g., string, boolean, integer, json
            $table->string("group")->default("general"); // e.g., general, mail, api, ai_agent
            $table->text("description")->nullable();
            $table->timestamps();
        });

        // Add some default settings
        DB::table("settings")->insert([
            ["key" => "site_name", "value" => "Rankolab", "type" => "string", "group" => "general", "description" => "The name of the application.", "created_at" => now(), "updated_at" => now()],
            ["key" => "admin_email", "value" => "admin@rankolab.com", "type" => "string", "group" => "general", "description" => "The primary administrative email address.", "created_at" => now(), "updated_at" => now()],
            ["key" => "pagination_limit", "value" => "15", "type" => "integer", "group" => "general", "description" => "Default number of items per page in tables.", "created_at" => now(), "updated_at" => now()],
            ["key" => "maintenance_mode", "value" => "false", "type" => "boolean", "group" => "general", "description" => "Enable or disable maintenance mode for the application.", "created_at" => now(), "updated_at" => now()],
            ["key" => "ai_agent_enabled", "value" => "true", "type" => "boolean", "group" => "ai_agent", "description" => "Enable or disable the AI Super Agent features.", "created_at" => now(), "updated_at" => now()],
            ["key" => "ai_agent_monitoring_interval", "value" => "5", "type" => "integer", "group" => "ai_agent", "description" => "Monitoring interval for the AI agent in minutes.", "created_at" => now(), "updated_at" => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("settings");
    }
};
