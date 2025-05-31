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
            // Check if the column doesn't already exist before adding
            if (!Schema::hasColumn("users", "role")) {
                // Add role column, default to 'user', index for faster lookups
                $table->string("role")->default("user")->after("email");
                $table->index("role"); 
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            // Check if the column exists before dropping
            if (Schema::hasColumn("users", "role")) {
                // Drop the index first, then the column
                $table->dropIndex(["role"]);
                $table->dropColumn("role");
            }
        });
    }
};




