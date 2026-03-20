<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::updateOrCreate(['name' => 'Purple HR Solutions'], [
            'address' => 'Banani, Dhaka - 1213',
            'email' => 'contact@purplehr.test',
            'phone' => '+8801700000000',
            'registration_no' => 'REG-2026-X01',
            'status' => 1
        ]);

        $branches = [
            ['name' => 'Main Head Office', 'address' => 'Dhaka'],
            ['name' => 'Chattogram Branch', 'address' => 'Agrabad'],
        ];

        foreach ($branches as $b) {
            $branch = Branch::updateOrCreate(['name' => $b['name'], 'company_id' => $company->id], $b);
            
            // Departments per branch
            $depts = ['IT & Software', 'Human Resources', 'Sales & Marketing', 'Accounts & Finance'];
            foreach ($depts as $d) {
                $dept = Department::updateOrCreate(['name' => $d, 'branch_id' => $branch->id], ['status' => 1]);
                
                // Designations per dept
                $desigs = ['Manager', 'Team Lead', 'Senior Executive', 'Junior Executive'];
                foreach ($desigs as $dg) {
                    Designation::updateOrCreate(['name' => $dg, 'department_id' => $dept->id], ['status' => 1]);
                }
            }
        }
    }
}
