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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // e.g., SL (Sick Leave), CL (Casual Leave)
            $table->integer('quota')->default(0); // Max days per year
            $table->boolean('is_accruable')->default(false); // If leave accrues monthly (e.g., 1 day/month)
            $table->boolean('requires_attachment')->default(false); // If medical doc or other is needed
            $table->string('color')->nullable();
            $table->boolean('is_paid')->default(true);
            $table->tinyInteger('status')->default(1); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
