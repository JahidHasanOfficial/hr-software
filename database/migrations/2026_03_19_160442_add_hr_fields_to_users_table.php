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
            $table->string('phone')->nullable()->after('email');
            $table->string('designation')->nullable()->after('phone');
            $table->date('joining_date')->nullable()->after('designation');
            $table->decimal('salary', 10, 2)->nullable()->after('joining_date');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('salary');
            $table->string('image')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'designation', 'joining_date', 'salary', 'status', 'image']);
        });
    }
};
