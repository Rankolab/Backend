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
        Schema::create("licenses", function (Blueprint $table) {
            $table->id();
            $table->uuid("key")->unique(); // Use UUID for license keys for uniqueness and non-guessability
            $table->string("type"); // e.g., Free, Basic, Pro, Enterprise - could link to a plans table later
            $table->enum("status", ["active", "inactive", "expired", "revoked"])->default("inactive");
            
            $table->foreignId("user_id")->nullable()->constrained("users")->onDelete("set null"); // Link to user
            $table->foreignId("website_id")->nullable()->constrained(table: "websites", column: "website_id")->onDelete("set null"); // Optional: Link to a specific website
            $table->foreignId("purchase_id")->nullable()->constrained("purchases")->onDelete("set null"); // Link to the purchase transaction
            $table->foreignId("plan_id")->nullable()->constrained("plans")->onDelete("set null"); // Link to the plan defining limits/features

            $table->timestamp("expires_at")->nullable(); // Nullable for perpetual licenses
            $table->timestamp("activated_at")->nullable();
            
            $table->unsignedInteger("max_activations")->default(1); // Max number of websites/instances allowed
            $table->unsignedInteger("current_activations")->default(0); // How many are currently active

            $table->json("metadata")->nullable(); // For storing extra data, e.g., specific feature overrides

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("licenses");
    }
};

