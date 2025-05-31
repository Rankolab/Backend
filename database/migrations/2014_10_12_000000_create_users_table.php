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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100)->nullable(); // As per docs
            $table->string('last_name', 100)->nullable(); // As per docs
            $table->string('email')->unique();
            $table->string('username', 50)->unique()->nullable(); // As per docs
            $table->string('password'); // Keep standard Laravel field
            $table->enum('role', ['user', 'affiliate', 'admin', 'super_admin'])->default('user'); // As per docs
            $table->enum('status', ['active', 'inactive', 'suspended', 'pending'])->default('pending'); // As per docs
            $table->timestamp('email_verified_at')->nullable(); // Keep standard Laravel field
            $table->boolean('email_verified')->default(false); // Add boolean field as per docs
            $table->boolean('two_factor_enabled')->default(false); // As per docs
            $table->text('two_factor_secret')->nullable(); // As per docs
            // affiliate_id FK added later
            $table->rememberToken(); // Keep standard Laravel field
            $table->timestamps();
        });

        // Drop the old 'name' column if it exists from the initial scaffold
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};


