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
        Schema::create('shifts', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name'); // Morning, Night, etc.
            $blueprint->time('start_time');
            $blueprint->time('end_time');
            $blueprint->integer('late_threshold')->default(15); // in minutes
            $blueprint->integer('early_checkout_threshold')->default(15); // in minutes
            $blueprint->integer('break_time')->default(60); // lunch break in minutes
            $blueprint->integer('half_day_threshold')->default(240); // min minutes for half day
            $blueprint->time('core_start_time')->nullable(); // Required for flexible shifts
            $blueprint->time('core_end_time')->nullable();
            $blueprint->boolean('is_flexible')->default(false);
            $blueprint->tinyInteger('status')->default(1);
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
