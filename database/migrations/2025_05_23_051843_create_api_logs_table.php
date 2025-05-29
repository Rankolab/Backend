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
        Schema::create("api_logs", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("api_service_id"); // Assuming it relates to an api_services table
            $table->string("endpoint"); // Endpoint called
            $table->string("method"); // HTTP method (GET, POST, etc.)
            $table->integer("status_code"); // HTTP status code returned
            $table->float("response_time"); // Response time in seconds or milliseconds
            $table->text("request_payload")->nullable(); // Optional: Log request data
            $table->text("response_payload")->nullable(); // Optional: Log response data
            $table->ipAddress("ip_address")->nullable(); // Optional: Log IP address
            $table->timestamps();

            // Optional: Add foreign key constraint if api_services table exists
            // $table->foreign("api_service_id")->references("id")->on("api_services")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("api_logs");
    }
};
