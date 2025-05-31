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
        Schema::table('websites', function (Blueprint $table) {
            $table->string('name')->nullable()->after('user_id');
            $table->string('url')->nullable()->after('name');
            $table->text('description')->nullable()->after('url');
            $table->string('status')->default('active')->after('description');
            $table->json('settings')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn(['name', 'url', 'description', 'status', 'settings']);
        });
    }
};
