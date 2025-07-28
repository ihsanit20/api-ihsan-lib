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
        Schema::create('visitor_records', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->date('last_visit_date')->nullable();
            $table->dateTime('last_visit_date_hour')->nullable();
            $table->timestamps();
        });

        Schema::create('visitor_daily_counts', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->unsignedInteger('visits')->default(0);
            $table->timestamps();
        });

        Schema::create('visitor_hourly_counts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedTinyInteger('hour');  // 0 to 23
            $table->unsignedInteger('visits')->default(0);
            $table->timestamps();

            $table->unique(['date', 'hour']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_records');
        Schema::dropIfExists('visitor_daily_counts');
        Schema::dropIfExists('visitor_hourly_counts');
    }
};
