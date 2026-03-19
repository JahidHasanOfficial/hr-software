<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = Branch::all();
        foreach ($branches as $branch) {
            Department::factory()->count(3)->create(['branch_id' => $branch->id]);
        }
    }
}
