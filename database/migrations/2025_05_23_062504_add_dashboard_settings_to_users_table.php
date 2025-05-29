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
        Schema::table("users", function (Blueprint $table) {
            // Add a JSON column to store dashboard widget preferences
            // Example structure: {"visible_widgets": ["revenue", "users", "system_health"], "widget_order": ["users", "revenue", "system_health"]}
            $table->json("dashboard_settings")->nullable()->after("remember_token");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn("dashboard_settings");
        });
    }
};

