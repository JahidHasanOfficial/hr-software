<?php

namespace Database\Seeders;

use App\Models\PayrollBatch;
use App\Models\Payslip;
use App\Models\User;
use App\Services\PayrollService;
use Illuminate\Database\Seeder;

class PayrollDummySeeder extends Seeder
{
    public function run(): void
    {
        $payrollService = app(PayrollService::class);
        $admin = User::role('Admin')->first();

        // Process February
        $payrollService->processBatch('02', '2026', $admin->id);
        
        // Mark as paid
        PayrollBatch::where('month', '02')->where('year', '2026')->update(['status' => 'paid']);
        Payslip::whereHas('batch', function($q) { $q->where('month', '02'); })->update(['status' => 'paid']);
    }
}
