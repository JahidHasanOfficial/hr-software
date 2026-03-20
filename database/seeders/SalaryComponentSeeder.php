<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\SalaryComponent;

class SalaryComponentSeeder extends Seeder
{
    public function run(): void
    {
        $components = [
            ['name' => 'Basic Salary', 'slug' => 'basic', 'type' => 'earning', 'is_taxable' => true],
            ['name' => 'HRA', 'slug' => 'hra', 'type' => 'earning', 'is_taxable' => true],
            ['name' => 'Medical Allowance', 'slug' => 'medical', 'type' => 'earning', 'is_taxable' => true],
            ['name' => 'Conveyance', 'slug' => 'conveyance', 'type' => 'earning', 'is_taxable' => false],
            ['name' => 'Provident Fund (PF)', 'slug' => 'pf', 'type' => 'deduction', 'is_statutory' => true],
            ['name' => 'Professional Tax (PT)', 'slug' => 'pt', 'type' => 'deduction', 'is_statutory' => true],
            ['name' => 'Income Tax (TDS)', 'slug' => 'tds', 'type' => 'deduction', 'is_statutory' => true],
            ['name' => 'Loan EMI', 'slug' => 'loan_emi', 'type' => 'deduction', 'is_statutory' => false],
            ['name' => 'Late Fine', 'slug' => 'late_fine', 'type' => 'deduction', 'is_statutory' => false],
        ];

        foreach ($components as $component) {
            SalaryComponent::updateOrCreate(['slug' => $component['slug']], $component);
        }
    }
}
