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
            // Add the license_id column, make it nullable and unsigned
            $table->unsignedBigInteger("license_id")->nullable()->after("user_id");

            // Add the foreign key constraint
            // Ensure the licenses table and its primary key (assuming 'id') exist
            $table->foreign("license_id")
                  ->references("id") // Assuming the primary key on licenses table is 'id'
                  ->on("licenses")
                  ->onDelete("set null"); // Set license_id to null if the license is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("websites", function (Blueprint $table) {
            // Drop the foreign key constraint first (Laravel generates a default name)
            // Check the actual constraint name if this fails
            $table->dropForeign(["license_id"]);

            // Drop the column
            $table->dropColumn("license_id");
        });
    }
};

