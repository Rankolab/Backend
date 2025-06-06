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
                Schema::create("chatbot_logs", function (Blueprint $table) {
            $table->id("log_id"); // Corresponds to BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->foreignId("user_id")->nullable()->constrained(table: "users", column: "id")->onDelete("set null");
            $table->foreignId("website_id")->nullable()->constrained("websites", "website_id")->onDelete("set null");
            $table->text("query");
            $table->text("response");
            $table->timestamps(); // Corresponds to created_at and updated_at TIMESTAMPs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("chatbot_logs");
    }
};
