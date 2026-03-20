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
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('payroll_batch_id')->constrained()->onDelete('cascade');
            $table->string('payslip_no')->unique();
            $table->decimal('basic_salary', 12, 2)->default(0.00);
            $table->decimal('total_earnings', 12, 2)->default(0.00);
            $table->decimal('total_deductions', 12, 2)->default(0.00);
            $table->decimal('net_payable', 12, 2)->default(0.00);
            $table->json('earnings_snapshot')->nullable();
            $table->json('deductions_snapshot')->nullable();
            $table->string('payment_method', 30)->default('bank_transfer');
            $table->string('status', 20)->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
