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
        Schema::create("subscriptions", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->foreignId("plan_id")->constrained()->onDelete("restrict");
            $table->string("stripe_id")->unique(); // Stripe Subscription ID
            $table->string("stripe_status"); // e.g., active, past_due, canceled, trialing
            $table->timestamp("trial_ends_at")->nullable();
            $table->timestamp("ends_at")->nullable(); // Cancellation date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("subscriptions");
    }
};
