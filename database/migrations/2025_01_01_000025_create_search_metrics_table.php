<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('search_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId("website_id")->constrained(table: "websites", column: "website_id")->onDelete("cascade");
            $table->date('date');
            $table->integer('clicks')->nullable();
            $table->integer('impressions')->nullable();
            $table->decimal('ctr', 5, 2)->nullable();
            $table->string('query')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_metrics');
    }
};
