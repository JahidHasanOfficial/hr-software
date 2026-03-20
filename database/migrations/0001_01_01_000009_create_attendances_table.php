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
        Schema::create('attendances', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->constrained()->cascadeOnDelete();
            $blueprint->foreignId('shift_id')->nullable()->constrained()->nullOnDelete();
            $blueprint->date('date');
            
            // Context info
            $blueprint->boolean('in_geofence')->default(false);
            $blueprint->string('device_info')->nullable();
            $blueprint->boolean('is_auto_checkout')->default(false);
            
            // Check-in info
            $blueprint->time('check_in_time')->nullable();
            $blueprint->decimal('check_in_latitude', 10, 8)->nullable();
            $blueprint->decimal('check_in_longitude', 11, 8)->nullable();
            $blueprint->string('check_in_ip')->nullable();

            // Check-out info
            $blueprint->time('check_out_time')->nullable();
            $blueprint->decimal('check_out_latitude', 10, 8)->nullable();
            $blueprint->decimal('check_out_longitude', 11, 8)->nullable();
            $blueprint->string('check_out_ip')->nullable();

            // Stats
            $blueprint->integer('stay_minutes')->nullable();
            $blueprint->integer('late_minutes')->default(0);
            $blueprint->integer('early_leaving_minutes')->default(0);
            $blueprint->integer('overtime_minutes')->default(0);
            
            $blueprint->tinyInteger('status')->default(0); // 0 = absent, 1 = present, 2 = late, 3 = half_day, 4 = leave
            $blueprint->text('notes')->nullable();
            
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
