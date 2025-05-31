<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('base_url')->nullable();
            $table->string('status')->default('unknown'); // online, offline, slow, unknown
            $table->integer('error_count')->default(0);
            $table->integer('response_time_ms')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_services');
    }
};
