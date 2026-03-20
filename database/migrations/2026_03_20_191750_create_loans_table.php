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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('loan_type', 50)->default('salary_advance');
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->decimal('emi_amount', 12, 2)->default(0.00);
            $table->decimal('paid_amount', 12, 2)->default(0.00);
            $table->decimal('remaining_amount', 12, 2)->default(0.00);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status', 20)->default('pending'); // pending, active, rejected, paid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
