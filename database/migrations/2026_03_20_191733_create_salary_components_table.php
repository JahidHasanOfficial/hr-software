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
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['earning', 'deduction', 'reimbursement'])->default('earning');
            $table->enum('unit', ['fixed', 'percentage'])->default('fixed');
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_statutory')->default(false); // e.g. PF, ESI
            $table->integer('status')->default(1); // 1: Active, 0: Inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_components');
    }
};
