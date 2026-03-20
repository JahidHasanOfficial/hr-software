<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeSalary;
use App\Models\EmployeeSalaryDetail;
use App\Models\SalaryComponent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();
        $dept = Department::first();
        $desig = Designation::first();
        $components = SalaryComponent::all();

        $employees = [
            ['name' => 'Super Admin', 'email' => 'admin@hr.test', 'role' => 'Admin', 'salary' => 150000],
            ['name' => 'HR Manager', 'email' => 'hr@hr.test', 'role' => 'HR Manager', 'salary' => 85000],
            ['name' => 'John Doe', 'email' => 'john@hr.test', 'role' => 'Employee', 'salary' => 55000],
            ['name' => 'Sarah Johnson', 'email' => 'sarah@hr.test', 'role' => 'Employee', 'salary' => 62000],
            ['name' => 'Michael Smith', 'email' => 'michael@hr.test', 'role' => 'Employee', 'salary' => 48000],
        ];

        foreach ($employees as $data) {
            $user = User::updateOrCreate(['email' => $data['email']], [
                'name' => $data['name'],
                'password' => Hash::make('password'),
                'phone' => '017'.rand(1000000, 9999999),
                'company_id' => $branch->company_id,
                'branch_id' => $branch->id,
                'department_id' => $dept->id,
                'designation_id' => $desig->id,
                'joining_date' => now()->subMonths(8)->toDateString(),
                'status' => 1
            ]);

            $user->assignRole($data['role']);

            // Setup Salary
            $gross = $data['salary'];
            $empSalary = EmployeeSalary::updateOrCreate(['user_id' => $user->id], [
                'gross_monthly' => $gross,
                'net_monthly' => $gross, // initially net = gross, will adjust below
                'status' => 1
            ]);

            // Assign standard components
            $basicVal = $gross * 0.50; // 50% Basic
            $hraVal = $gross * 0.30; // 30% HRA
            $medicalVal = $gross * 0.10; // 10% Med
            $pfVal = $basicVal * 0.12; // 12% PF

            $map = [
                'basic' => $basicVal,
                'hra' => $hraVal,
                'medical' => $medicalVal,
                'pf' => $pfVal
            ];

            $totalEarnings = 0; $totalDeductions = 0;
            foreach ($map as $slug => $val) {
                $comp = $components->where('slug', $slug)->first();
                if ($comp) {
                    EmployeeSalaryDetail::create([
                        'employee_salary_id' => $empSalary->id,
                        'salary_component_id' => $comp->id,
                        'value' => $val
                    ]);

                    if ($comp->type == 'earning') $totalEarnings += $val;
                    else $totalDeductions += $val;
                }
            }

            $empSalary->update(['net_monthly' => $totalEarnings - $totalDeductions]);
        }
    }
}
