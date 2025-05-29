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
        Schema::create("payments", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->foreignId("subscription_id")->nullable()->constrained()->onDelete("set null"); // Link to subscription if applicable
            $table->string("stripe_payment_intent_id")->unique(); // Stripe Payment Intent ID
            $table->decimal("amount", 10, 2); // Amount paid
            $table->string("currency", 3); // e.g., usd, eur
            $table->string("status"); // e.g., succeeded, requires_payment_method, failed
            $table->string("description")->nullable();
            $table->json("payment_method_details")->nullable(); // e.g., card brand, last4
            $table->string("stripe_invoice_id")->nullable()->index(); // Link to Stripe Invoice if applicable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("payments");
    }
};
