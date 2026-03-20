<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\EmployeeSalary;
use App\Models\EmployeeSalaryDetail;
use App\Models\SalaryComponent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeSalaryController extends Controller
{
    public function index()
    {
        $this->authorize('payroll management');
        $users = User::where('status', 1)->with('salarySetup')->latest()->get();
        return view('backend.pages.payroll.salary.index', compact('users'));
    }

    public function edit($userId)
    {
        $this->authorize('payroll management');
        $user = User::with('salarySetup.details')->findOrFail($userId);
        $salary = $user->salarySetup;
        $salaryDetails = $salary ? $salary->details->pluck('value', 'salary_component_id')->toArray() : [];
        $components = SalaryComponent::where('status', 1)->get();
        
        return view('backend.pages.payroll.salary.edit', compact('user', 'salary', 'components', 'salaryDetails'));
    }

    public function update(Request $request, $userId)
    {
        $this->authorize('payroll management');
        
        $request->validate([
            'gross_monthly' => 'required|numeric|min:0',
            'components' => 'required|array'
        ]);

        return DB::transaction(function () use ($request, $userId) {
            $salary = EmployeeSalary::updateOrCreate(
                ['user_id' => $userId],
                [
                    'gross_monthly' => $request->gross_monthly,
                    'currency' => $request->currency ?? 'BDT',
                    'status' => 1
                ]
            );

            // Re-calculate Net and Update Details
            $totalEarning = 0;
            $totalDeduction = 0;

            // Clear old details
            $salary->details()->delete();

            foreach ($request->components as $compId => $value) {
                if ($value > 0) {
                    $component = SalaryComponent::find($compId);
                    if ($component) {
                        EmployeeSalaryDetail::create([
                            'employee_salary_id' => $salary->id,
                            'salary_component_id' => $compId,
                            'value' => $value
                        ]);

                        if ($component->type == 'earning') {
                            $totalEarning += $value;
                        } elseif ($component->type == 'deduction') {
                            $totalDeduction += $value;
                        }
                    }
                }
            }

            $salary->update(['net_monthly' => $totalEarning - $totalDeduction]);

            return redirect()->route('employee-salary.index')->with('success', 'Salary setup updated.');
        });
    }
}
