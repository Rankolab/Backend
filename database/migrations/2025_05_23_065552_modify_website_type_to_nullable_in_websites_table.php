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
        Schema::table("websites", function (Blueprint $table) {
            // Modify the website_type column to be nullable
            // Ensure the column exists before attempting to change it
            if (Schema::hasColumn("websites", "website_type")) {
                $table->string("website_type")->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("websites", function (Blueprint $table) {
            // Revert the website_type column to not nullable
            // Note: This assumes it was originally not nullable. Adjust if needed.
            // Also, consider if a default value is appropriate when reverting.
            if (Schema::hasColumn("websites", "website_type")) {
                 // Reverting nullable() change might require specifying a default or handling existing nulls
                 // For simplicity, we just change it back. Data loss might occur if nulls exist.
                $table->string("website_type")->nullable(false)->change();
            }
        });
    }
};

