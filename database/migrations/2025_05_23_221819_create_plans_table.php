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
        Schema::create("plans", function (Blueprint $table) {
            $table->id();
            $table->string("name"); // e.g., Free, Basic, Pro, Enterprise
            $table->text("description")->nullable();
            $table->decimal("price", 8, 2)->default(0.00);
            $table->string("interval")->default("month"); // e.g., month, year
            $table->string("stripe_id")->nullable()->unique(); // Stripe Price ID
            $table->json("features")->nullable(); // List of features included
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("plans");
    }
};
