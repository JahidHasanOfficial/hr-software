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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')->constrained()->onDelete('set null');
            $table->foreignId('branch_id')->nullable()->after('company_id')->constrained()->onDelete('set null');
            $table->foreignId('department_id')->nullable()->after('branch_id')->constrained()->onDelete('set null');
            $table->foreignId('designation_id')->nullable()->after('department_id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['designation_id']);
            $table->dropColumn(['company_id', 'branch_id', 'department_id', 'designation_id']);
        });
    }
};
