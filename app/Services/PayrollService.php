<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\EmployeeSalary;
use App\Models\Loan;
use App\Models\PayrollBatch;
use App\Models\Payslip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    /**
     * Get Payroll Preview for all active employees for a given month/year
     */
    public function getPayrollPreview($month, $year)
    {
        $employees = User::where('status', 1)->whereHas('salary')->with(['salary.details.component', 'designation', 'department'])->get();
        $previewData = [];

        foreach ($employees as $employee) {
            $calc = $this->calculateEmployeePayroll($employee, $month, $year);
            $previewData[] = [
                'employee' => $employee,
                'calc' => $calc
            ];
        }

        return $previewData;
    }

    /**
     * The core calculation logic for an individual employee
     */
    public function calculateEmployeePayroll($employee, $month, $year)
    {
        $salary = $employee->salarySetup;
        $earnings = [];
        $deductions = [];
        
        $basic = 0;
        $totalGross = $salary->gross_monthly;
        
        // 1. Process Defined Components
        foreach ($salary->details as $detail) {
            $comp = $detail->component;
            $val = $detail->value;
            $actualValue = ($comp->unit == 'percentage') ? ($totalGross * ($val / 100)) : $val;
            
            if ($comp->slug == 'basic') $basic = $actualValue;

            if ($comp->type == 'earning') {
                $earnings[$comp->slug] = ['name' => $comp->name, 'value' => $actualValue];
            } else {
                $deductions[$comp->slug] = ['name' => $comp->name, 'value' => $actualValue];
            }
        }

        // 2. Attendance-Based Calculations (ABSENT DEDUCTION)
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        // Count Days that have attendance records
        $attendanceCount = Attendance::where('user_id', $employee->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereIn('status', [1, 2, 3, 4, 5, 6]) 
            ->count();
            
        $totalDaysInMonth = $startDate->daysInMonth;
        $absentDays = $totalDaysInMonth - $attendanceCount;
        
        if ($absentDays > 0) {
            $unpaidFine = ($totalGross / $totalDaysInMonth) * $absentDays;
            $deductions['absent_deduction'] = [
                'name' => 'Absent Deduction ('.$absentDays.' days)', 
                'value' => round($unpaidFine, 2)
            ];
        }

        // 3. Loan EMI Deductions
        $activeLoans = Loan::where('user_id', $employee->id)->where('status', 'active')->get();
        foreach ($activeLoans as $loan) {
            $deductions['loan_'.$loan->id] = ['name' => 'Loan EMI: '.$loan->loan_type, 'value' => $loan->emi_amount];
        }

        $totalEarnings = collect($earnings)->sum('value');
        $totalDeductions = collect($deductions)->sum('value');

        return [
            'earnings' => $earnings,
            'deductions' => $deductions,
            'total_earnings' => $totalEarnings,
            'total_deductions' => $totalDeductions,
            'net_payable' => $totalEarnings - $totalDeductions,
            'basic' => $basic
        ];
    }

    /**
     * Process and Save Payroll Batch and Payslips
     */
    public function processBatch($month, $year, $processedBy)
    {
        return DB::transaction(function () use ($month, $year, $processedBy) {
            $batch = PayrollBatch::updateOrCreate(
                ['month' => $month, 'year' => $year],
                [
                    'status' => 'locked',
                    'processed_by' => $processedBy,
                    'approved_at' => now()
                ]
            );

            $employees = User::where('status', 1)->whereHas('salarySetup')->get();
            $batchGross = 0; $batchNet = 0; $batchDeduct = 0;

            foreach ($employees as $employee) {
                $calc = $this->calculateEmployeePayroll($employee, $month, $year);
                
                Payslip::updateOrCreate(
                    ['user_id' => $employee->id, 'payroll_batch_id' => $batch->id],
                    [
                        'payslip_no' => 'PS-'.date('ymd').'-'.str_pad($employee->id, 4, '0', STR_PAD_LEFT),
                        'basic_salary' => $calc['basic'],
                        'total_earnings' => $calc['total_earnings'],
                        'total_deductions' => $calc['total_deductions'],
                        'net_payable' => $calc['net_payable'],
                        'earnings_snapshot' => $calc['earnings'],
                        'deductions_snapshot' => $calc['deductions'],
                        'status' => 'unpaid'
                    ]
                );

                $batchGross += $calc['total_earnings'];
                $batchDeduct += $calc['total_deductions'];
                $batchNet += $calc['net_payable'];
            }

            $batch->update([
                'total_gross' => $batchGross,
                'total_deductions' => $batchDeduct,
                'total_net' => $batchNet
            ]);

            return $batch;
        });
    }
}
