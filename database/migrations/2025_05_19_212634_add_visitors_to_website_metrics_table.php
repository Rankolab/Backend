<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('website_metrics', function (Blueprint $table) {
            // Add 'visitors' column safely without using 'after' if column 'date' is missing
            if (!Schema::hasColumn('website_metrics', 'visitors')) {
                $table->integer('visitors')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('website_metrics', function (Blueprint $table) {
            if (Schema::hasColumn('website_metrics', 'visitors')) {
                $table->dropColumn('visitors');
            }
        });
    }
};
