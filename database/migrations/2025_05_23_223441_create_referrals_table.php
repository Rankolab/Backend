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
        Schema::create("referrals", function (Blueprint $table) {
            $table->id();
            $table->foreignId("affiliate_id")->constrained()->onDelete("cascade");
            $table->foreignId("referred_user_id")->constrained("users")->onDelete("cascade");
            $table->timestamp("converted_at")->nullable(); // Timestamp when the referred user made a purchase or completed a target action
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("referrals");
    }
};
