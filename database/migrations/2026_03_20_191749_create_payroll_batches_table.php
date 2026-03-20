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
        Schema::create('payroll_batches', function (Blueprint $table) {
            $table->id();
            $table->string('month', 2);
            $table->string('year', 4);
            $table->decimal('total_gross', 15, 2)->default(0.00);
            $table->decimal('total_deductions', 15, 2)->default(0.00);
            $table->decimal('total_net', 15, 2)->default(0.00);
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status', 20)->default('draft'); // draft, locked, paid
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_batches');
    }
};
