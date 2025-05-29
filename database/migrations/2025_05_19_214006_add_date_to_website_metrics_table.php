<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('website_metrics', function (Blueprint $table) {
            $table->date('date')->nullable(); // Removed `->after('id')`
        });
    }

    public function down()
    {
        Schema::table('website_metrics', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
};
