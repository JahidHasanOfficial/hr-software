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
        Schema::create('attendance_logs', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('attendance_id')->constrained()->cascadeOnDelete();
            $blueprint->foreignId('user_id')->constrained()->cascadeOnDelete();
            $blueprint->string('type'); // check_in or check_out
            $blueprint->time('time');
            $blueprint->decimal('latitude', 10, 8)->nullable();
            $blueprint->decimal('longitude', 11, 8)->nullable();
            $blueprint->string('ip')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
